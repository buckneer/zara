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

            // images
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',

            // variants (optional array of arrays)
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

            // handle inline variants (create)
            if ($request->has('variants') && is_array($request->input('variants'))) {
                foreach ($request->input('variants') as $v) {
                    // skip entirely blank variant rows
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

            // handle uploaded images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $idx => $file) {
                    $path = $file->store('products', 'public'); // products/abcd.jpg
                    $product->images()->create([
                        'path' => $path,
                        'alt' => $product->title,
                        'position' => $idx,
                        'is_primary' => $idx === 0, // default first uploaded as primary if no other set
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

            // images
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'existing_primary' => 'nullable|integer', // id of current image to set primary
            'delete_image_ids' => 'nullable|array',
            'delete_image_ids.*' => 'integer|exists:product_images,id',

            // variants inline
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

            // sync categories
            $product->categories()->sync($data['category_ids'] ?? []);

            // handle deletion of existing images (if requested)
            if (!empty($data['delete_image_ids'])) {
                $toDelete = \App\Models\ProductImage::whereIn('id', $data['delete_image_ids'])->where('product_id', $product->id)->get();
                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }

            // handle newly uploaded images
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

            // set primary image (existing_primary is image id)
            if ($request->filled('existing_primary')) {
                $primaryId = (int)$request->input('existing_primary');
                // set all images is_primary false then set selected true
                $product->images()->update(['is_primary' => false]);
                $img = $product->images()->where('id', $primaryId)->first();
                if ($img) {
                    $img->is_primary = true;
                    $img->save();
                }
            } else {
                // if no primary supplied, ensure at least one primary exists
                if ($product->images()->where('is_primary', true)->count() === 0) {
                    $first = $product->images()->first();
                    if ($first) {
                        $first->is_primary = true;
                        $first->save();
                    }
                }
            }

            // variants handling: create / update / delete
            if ($request->has('variants') && is_array($request->input('variants'))) {
                foreach ($request->input('variants') as $v) {
                    // if id present -> update or delete
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
                        // create new variant (skip if entirely blank)
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
        $product->delete(); // soft delete
        return $request->wantsJson()
            ? response()->json(['message' => 'deleted'])
            : redirect()->route('products.index')->with('success', 'Product removed.');
    }
}
