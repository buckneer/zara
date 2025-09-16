<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'title',
        'slug',
        'sku',
        'description',
        'price',
        'brand',
        'meta',
        'active',
        'position',
    ];

    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
