<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $fillable = [
        'title', 
        'file_path', 
        'media_type',
        'type', 
        'featured'
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    // Append an absolute URL attribute automatically for Inertia frontend delivery
    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }
}