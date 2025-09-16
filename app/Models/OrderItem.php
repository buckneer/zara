<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrderItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'sku',
        'title',
        'quantity',
        'unit_price',
        'total_price',
        'meta',
    ];


    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'meta' => 'array',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
