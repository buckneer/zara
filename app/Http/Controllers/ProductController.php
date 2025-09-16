<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants','categories')->orderBy('created_at','desc')->paginate(20);
        return request()->wantsJson() ? response()->json($products) : view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:products,slug',
            'sku' => 'nullable|string|max:191|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:191',
            'meta' => 'nullable|array',
            'active' => 'nullable|boolean',
            'position' => 'nullable|integer',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($data, $request, &$product) {
            $product = Product::create($data);
            if (!empty($data['category_ids'])) {
                $product->categories()->sync($data['category_ids']);
            }
            // Optionally handle inline variants: request->variants as array
            if ($request->has('variants') && is_array($request->input('variants'))) {
                foreach ($request->input('variants') as $v) {
                    $product->variants()->create([
                        'sku' => $v['sku'] ?? null,
                        'name' => $v['name'] ?? null,
                        'size' => $v['size'] ?? null,
                        'color' => $v['color'] ?? null,
                        'price' => $v['price'] ?? null,
                        'stock' => $v['stock'] ?? 0,
                        'backorder' => $v['backorder'] ?? false,
                        'attributes' => $v['attributes'] ?? null,
                    ]);
                }
            }
        });

        return $request->wantsJson()
            ? response()->json($product, 201)
            : redirect()->route('products.index')->with('success','Product created.');
    }

    public function show(Product $product)
    {
        $product->load('variants','images','categories','reviews');
        return request()->wantsJson() ? response()->json($product) : view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:products,slug,'.$product->id,
            'sku' => 'nullable|string|max:191|unique:products,sku,'.$product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:191',
            'meta' => 'nullable|array',
            'active' => 'nullable|boolean',
            'position' => 'nullable|integer',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($data, $product) {
            $product->update($data);
            $product->categories()->sync($data['category_ids'] ?? []);
        });

        return $request->wantsJson()
            ? response()->json($product)
            : redirect()->route('products.index')->with('success','Product updated.');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete(); // soft delete
        return $request->wantsJson()
            ? response()->json(['message'=>'deleted'])
            : redirect()->route('products.index')->with('success','Product removed.');
    }
}
