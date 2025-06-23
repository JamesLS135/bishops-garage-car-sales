<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'path',
        'is_primary',
    ];

    /**
     * Get the publicly accessible URL for the image.
     */
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }
}