<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // This automatically creates a slug (like "Signature Coffee" -> "signature-coffee") 
    // whenever you save a category name.
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}