<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'size',
        'color',
        'price',
        'stock',
        'backorder',
        'attributes',
    ];


    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'backorder' => 'boolean',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }


    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'variant_id');
    }
}
