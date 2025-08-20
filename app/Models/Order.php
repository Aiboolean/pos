<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'total_price',
        'amount_received',
        'change',
        'user_id', // Add user_id to fillable
    ];

    /**
     * Get the user who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    //For filtering in Order Management
    public function scopeFilterByDate($query, $filters)
{
    if (!empty($filters['date'])) {
        $query->whereDate('created_at', $filters['date']);
    }
    if (!empty($filters['month'])) {
        $query->whereMonth('created_at', $filters['month']);
    }
    if (!empty($filters['year'])) {
        $query->whereYear('created_at', $filters['year']);
    }
    return $query;
}
}

