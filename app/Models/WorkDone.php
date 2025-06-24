<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDone extends Model
{
    use HasFactory;

    protected $primaryKey = 'work_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'mechanic_id',
        'work_date',
        'description',
        'parts_used',
        'cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'work_date' => 'datetime',
    ];

    /**
     * Get the car that this work record belongs to.
     */
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * Get the user (mechanic) who performed the work.
     */
    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}