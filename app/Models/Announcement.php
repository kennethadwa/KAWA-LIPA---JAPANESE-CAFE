<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'status',
        'expires_at',
    ];

    /**
     * Optional: Cast the expiration date automatically into a clean date object
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}