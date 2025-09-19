<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants', 'categories')->orderBy('created_at', 'desc')->paginate(20);
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
            'discount_percent' => 'nullable|numeric|min:0|max:100',

            
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',

            
            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|max:191',
            'variants.*.name' => 'nullable|string|max:191',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer',
            'variants.*.backorder' => 'nullable|boolean',
            'variants.*.attributes' => 'nullable|array',

        ]);

        DB::transaction(function () use ($data, $request, &$product) {
            $product = Product::create($data);

            if (!empty($data['category_ids'])) {
                $product->categories()->sync($data['category_ids']);
            }

            
            if ($request->has('variants') && is_array($request->input('variants'))) {
                foreach ($request->input('variants') as $v) {
                    
                    if (empty(Arr::filter($v))) continue;
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

            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $idx => $file) {
                    $path = $file->store('products', 'public'); 
                    $product->images()->create([
                        'path' => $path,
                        'alt' => $product->title,
                        'position' => $idx,
                        'is_primary' => $idx === 0, 
                    ]);
                }
            }
        });

        return $request->wantsJson()
            ? response()->json($product, 201)
            : redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function show(Product $product)
    {
        $product->load('variants', 'images', 'categories', 'reviews');
        return request()->wantsJson() ? response()->json($product) : view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:products,slug,' . $product->id,
            'sku' => 'nullable|string|max:191|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand' => 'nullable|string|max:191',
            'meta' => 'nullable|array',
            'active' => 'nullable|boolean',
            'position' => 'nullable|integer',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',

            
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'existing_primary' => 'nullable|integer', 
            'delete_image_ids' => 'nullable|array',
            'delete_image_ids.*' => 'integer|exists:product_images,id',

            
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.sku' => 'nullable|string|max:191',
            'variants.*.name' => 'nullable|string|max:191',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer',
            'variants.*.backorder' => 'nullable|boolean',
            'variants.*.attributes' => 'nullable|array',
            'variants.*._destroy' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($data, $request, $product) {

            $product->update($data);

            
            $product->categories()->sync($data['category_ids'] ?? []);

            
            if (!empty($data['delete_image_ids'])) {
                $toDelete = \App\Models\ProductImage::whereIn('id', $data['delete_image_ids'])->where('product_id', $product->id)->get();
                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }

            
            if ($request->hasFile('images')) {
                $currentCount = $product->images()->count();
                foreach ($request->file('images') as $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'path' => $path,
                        'alt' => $product->title,
                        'position' => $currentCount++,
                        'is_primary' => false,
                    ]);
                }
            }

            
            if ($request->filled('existing_primary')) {
                $primaryId = (int)$request->input('existing_primary');
                
                $product->images()->update(['is_primary' => false]);
                $img = $product->images()->where('id', $primaryId)->first();
                if ($img) {
                    $img->is_primary = true;
                    $img->save();
                }
            } else {
                
                if ($product->images()->where('is_primary', true)->count() === 0) {
                    $first = $product->images()->first();
                    if ($first) {
                        $first->is_primary = true;
                        $first->save();
                    }
                }
            }

            
            if ($request->has('variants') && is_array($request->input('variants'))) {
                foreach ($request->input('variants') as $v) {
                    
                    if (!empty($v['id'])) {
                        $variant = ProductVariant::where('id', $v['id'])->where('product_id', $product->id)->first();
                        if (! $variant) continue;
                        if (!empty($v['_destroy'])) {
                            $variant->delete();
                            continue;
                        }
                        $variant->update([
                            'sku' => $v['sku'] ?? $variant->sku,
                            'name' => $v['name'] ?? $variant->name,
                            'size' => $v['size'] ?? $variant->size,
                            'color' => $v['color'] ?? $variant->color,
                            'price' => $v['price'] ?? $variant->price,
                            'stock' => $v['stock'] ?? $variant->stock,
                            'backorder' => $v['backorder'] ?? $variant->backorder,
                            'attributes' => $v['attributes'] ?? $variant->attributes,
                        ]);
                    } else {
                        
                        if (empty(Arr::filter($v))) continue;
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
            }
        });

        return $request->wantsJson()
            ? response()->json($product)
            : redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Request $request, Product $product)
    {
        $product->delete(); 
        return $request->wantsJson()
            ? response()->json(['message' => 'deleted'])
            : redirect()->route('products.index')->with('success', 'Product removed.');
    }
}
