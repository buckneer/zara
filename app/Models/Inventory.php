<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventory extends Model
{
    use HasFactory;


    protected $fillable = [
        'variant_id',
        'quantity_change',
        'quantity_after',
        'reason',
        'user_id',
        'metadata',
    ];


    protected $casts = [
        'metadata' => 'array',
    ];


    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
