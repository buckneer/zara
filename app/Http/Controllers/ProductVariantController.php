<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->paginate(30);
        return request()->wantsJson() ? response()->json($variants) : view('admin.variants.index', compact('product','variants'));
    }

    public function create(Product $product)
    {
        return view('admin.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => 'nullable|string|unique:product_variants,sku',
            'name' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer',
            'backorder' => 'nullable|boolean',
            'attributes' => 'nullable|array',
        ]);

        $variant = $product->variants()->create($data);

        return $request->wantsJson()
            ? response()->json($variant, 201)
            : redirect()->route('products.show', $product)->with('success','Variant created.');
    }

    public function show(Product $product, ProductVariant $variant)
    {
        return request()->wantsJson() ? response()->json($variant) : view('admin.variants.show', compact('product','variant'));
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.variants.edit', compact('product','variant'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $data = $request->validate([
            'sku' => ['nullable', Rule::unique('product_variants','sku')->ignore($variant->id)],
            'name' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer',
            'backorder' => 'nullable|boolean',
            'attributes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($variant, $data) {
            $variant->update($data);
        });

        return $request->wantsJson() ? response()->json($variant) : redirect()->route('products.show', $product)->with('success','Variant updated.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return request()->wantsJson() ? response()->json(['message'=>'deleted']) : redirect()->route('products.show', $product)->with('success','Variant deleted.');
    }
}
