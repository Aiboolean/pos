<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'total_price',
        'amount_received', // Add this
        'change', // Add this
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

