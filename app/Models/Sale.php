<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey = 'sale_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'sale_date',
        'selling_price',
        'customer_id',
        'odometer_at_sale',
        'warranty_details',
        'salesperson_name',
        'part_exchange_value',
        'part_exchange_car_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale_date' => 'datetime',
    ];

    /**
     * Get the car that was sold.
     */
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * Get the car that was taken as a part-exchange in this sale.
     */
    public function partExchangeCar()
    {
        return $this->belongsTo(Car::class, 'part_exchange_car_id');
    }

    /**
     * Get the customer for this sale.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}