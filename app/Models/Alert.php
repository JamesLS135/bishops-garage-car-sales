<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_id',
        'type',
        'message',
        'due_date',
        'level',
    ];

    /**
     * Get the car that this alert belongs to.
     */
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}