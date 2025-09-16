<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'transaction_id',
        'status',
        'details',
        'paid_at',
    ];


    protected $casts = [
        'amount' => 'decimal:2',
        'details' => 'array',
        'paid_at' => 'datetime',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
