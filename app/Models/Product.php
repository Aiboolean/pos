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
        'is_available',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
