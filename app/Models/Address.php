<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Address extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'name',
        'company',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'notes',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function ordersBilling()
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }


    public function ordersShipping()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }
}
