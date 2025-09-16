<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_number',
        'user_id',
        'billing_address_id',
        'shipping_address_id',
        'subtotal',
        'shipping_total',
        'tax_total',
        'discount_total',
        'grand_total',
        'status',
        'shipping_method',
        'shipping_tracking',
        'payment_status',
        'meta',
        'placed_at',
    ];


    protected $casts = [
        'meta' => 'array',
        'subtotal' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'placed_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }


    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
