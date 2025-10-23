<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $fillable = ['name', 'unit', 'stock', 'alert_threshold'];
    
    protected $appends = ['last_updated', 'usage_today', 'status'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredient')
            ->withPivot('quantity');
    }

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

    /**
     * Get ingredient usage within a specific period from stock history
     */
    public function getUsageInPeriod($startDate, $endDate)
    {
        return abs($this->stockHistories()
            ->where('change_type', 'order_deduction')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('change_amount'));
    }

    /**
     * Get stock movements within a specific period
     */
   /**
 * Get stock movements within a specific period (ALL types)
 */
    public function getStockMovementsInPeriod($startDate, $endDate)
    {
        return $this->stockHistories()
            ->with(['user', 'order'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Method to record stock changes - ADD THIS METHOD
     */
    public function recordStockChange($newStock, $changeType, $reason = null, $orderId = null, $effectiveDate = null)
    {
        $changeAmount = $newStock - $this->stock;
        
        StockHistory::create([
            'ingredient_id' => $this->id,
            'previous_stock' => $this->stock,
            'new_stock' => $newStock,
            'change_amount' => $changeAmount,
            'change_type' => $changeType,
            'reason' => $reason,
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'effective_date' => $effectiveDate ?: now()
        ]);

        // Update the current stock
        $this->update(['stock' => $newStock]);
    }

    /**
     * Get stock at a specific date
     */
    public function getStockAtDate($date)
    {
        // Find the last stock history record before or at the given date
        $history = $this->stockHistories()
            ->where('effective_date', '<=', $date)
            ->orderBy('effective_date', 'desc')
            ->first();

        return $history ? $history->new_stock : $this->stock;
    }

    /**
     * Get initial stock for a period (stock at start date)
     */
    public function getInitialStockForPeriod($startDate)
    {
        return $this->getStockAtDate($startDate->copy()->subSecond());
    }
}