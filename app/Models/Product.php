<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'has_multiple_sizes',
        'price_small',
        'price_medium',
        'price_large',
        'price',
        'small_enabled',
        'medium_enabled',
        'large_enabled',
        'is_available',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

   // app/Models/Product.php
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
            ->withPivot(['quantity', 'small_multiplier', 'medium_multiplier', 'large_multiplier']);
    }
    /**
     * Calculate how many of this product can be made with current ingredient stock
     */
    public function calculateAvailability()
    {
        $availability = [];
        
        if ($this->has_multiple_sizes) {
            // Calculate for each size
            if ($this->small_enabled && $this->price_small) {
                $availability['small'] = $this->calculateSizeAvailability('small');
            }
            if ($this->medium_enabled && $this->price_medium) {
                $availability['medium'] = $this->calculateSizeAvailability('medium');
            }
            if ($this->large_enabled && $this->price_large) {
                $availability['large'] = $this->calculateSizeAvailability('large');
            }
        } else {
            // Single size product
            $availability['single'] = $this->calculateSizeAvailability('single');
        }
        
        return $availability;
    }

    /**
     * Calculate availability for a specific size
     */
    private function calculateSizeAvailability($size)
    {
        $maxAvailable = PHP_INT_MAX;
        
        foreach ($this->ingredients as $ingredient) {
            // Get the multiplier for this size
            $multiplier = match($size) {
                'small' => $ingredient->pivot->small_multiplier ?? 0.75,
                'medium' => $ingredient->pivot->medium_multiplier ?? 1.00,
                'large' => $ingredient->pivot->large_multiplier ?? 1.50,
                'single' => 1.00,
                default => 1.00
            };
            
            // Calculate how much of this ingredient is needed per product
            $quantityPerProduct = $ingredient->pivot->quantity * $multiplier;
            
            if ($quantityPerProduct <= 0) {
                continue; // Skip if no quantity needed
            }
            
            // Calculate how many products can be made with this ingredient
            $availableFromIngredient = floor($ingredient->stock / $quantityPerProduct);
            
            // The limiting factor is the ingredient with the lowest availability
            $maxAvailable = min($maxAvailable, $availableFromIngredient);
        }
        
        // If no ingredients or all ingredients have infinite stock
        return $maxAvailable === PHP_INT_MAX ? 999 : max(0, $maxAvailable);
    }

}