<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    
protected $fillable = ['name', 'unit', 'stock', 'alert_threshold'];

public function products()
{
    return $this->belongsToMany(Product::class, 'product_ingredient')
        ->withPivot('quantity');
}

}
