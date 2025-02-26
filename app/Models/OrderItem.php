<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 
        'product_id', 
        'user_id', // Add user_id to fillable
        'quantity', 
        'price', 
        'size'
    ];

    /**
     * Get the product associated with the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who placed the order item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}