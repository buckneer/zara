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
        'discount_percent',

    ];

    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
        'active' => 'boolean',
        'discount_percent' => 'decimal:2',
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


    public function getDiscountedPriceAttribute()
    {
        $percent = (float) ($this->discount_percent ?? 0);
        $price = (float) ($this->price ?? 0);

        if ($percent <= 0) {
            return number_format($price, 2, '.', '');
        }

        $discounted = ($price * (100 - $percent)) / 100;
        return number_format($discounted >= 0 ? $discounted : 0, 2, '.', '');
    }
}
