<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'image' => 'required|image|max:5120', // 5MB
            'variant_id' => 'nullable|exists:product_variants,id',
            'alt' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'is_primary' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('public/products');

        $image = ProductImage::create([
            'product_id' => $product->id,
            'variant_id' => $data['variant_id'] ?? null,
            'path' => $path,
            'alt' => $data['alt'] ?? null,
            'position' => $data['position'] ?? 0,
            'is_primary' => $data['is_primary'] ?? false,
        ]);

        return $request->wantsJson() ? response()->json($image, 201) : redirect()->back()->with('success','Image uploaded.');
    }

    public function destroy(Request $request, Product $product, ProductImage $image)
    {
        if (Storage::exists($image->path)) {
            Storage::delete($image->path);
        }
        $image->delete();

        return $request->wantsJson() ? response()->json(['message'=>'deleted']) : redirect()->back()->with('success','Image removed.');
    }
}
