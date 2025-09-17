<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Helper: total value of the cart (sum of unit_price * qty).
     */
    public function total(): float
    {
        return $this->items->reduce(function ($carry, $item) {
            $price = $item->unit_price ?? ($item->product->price ?? 0);
            return $carry + ($price * $item->qty);
        }, 0.0);
    }
}
