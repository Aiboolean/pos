<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'ingredient_id',
        'previous_stock',
        'new_stock',
        'change_amount',
        'change_type',
        'reason',
        'user_id',
        'order_id',
        'effective_date' // Make sure this is included
    ];

    protected $casts = [
        'effective_date' => 'datetime',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}