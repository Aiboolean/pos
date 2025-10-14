<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'unit', 'stock', 'alert_threshold'];
    
    // Add these for tracking
    protected $appends = ['last_updated', 'usage_today', 'status'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredient')
            ->withPivot('quantity');
    }

    // Add relationship to track stock changes (if you don't have this yet)
    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    // Accessor for last updated date
    public function getLastUpdatedAttribute()
    {
        return $this->updated_at->format('M j, Y g:i A');
    }

    // Accessor for low stock status
    public function getStatusAttribute()
    {
        if ($this->stock == 0) {
            return 'out-of-stock';
        } elseif ($this->stock <= $this->alert_threshold) {
            return 'low-stock';
        } else {
            return 'in-stock';
        }
    }

    // Method to get usage in a date range
    public function getUsageInPeriod($startDate, $endDate)
    {
        // This will depend on how you track order deductions
        // You might need to create an OrderIngredientUsage model
        return OrderItem::join('product_ingredient', 'order_items.product_id', '=', 'product_ingredient.product_id')
            ->where('product_ingredient.ingredient_id', $this->id)
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->selectRaw('SUM(product_ingredient.quantity * order_items.quantity) as total_used')
            ->first()
            ->total_used ?? 0;
    }
}