<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'category_id', // Changed from 'category' to match your foreign key
        'is_available', 
        'image_path'
    ];

    /**
     * Get the category that owns the menu item.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}