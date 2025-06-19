<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vin',
        'registration_number',
        'make',
        'model',
        'year',
        'odometer_reading',
        'current_status',
        // Add any other fields from your create/edit forms here
        'variant_trim',
        'body_type',
        'color',
        'fuel_type',
        'engine_size',
        'transmission_type',
        'number_of_doors',
        'number_of_seats',
        'number_of_keys_present',
        'tax_book_status',
        'validated_status',
        'window_sticker_present',
        'condition',
        'notes_internal',
    ];

    // --- ATTRIBUTE MUTATORS ---

    /**
     * Always set the VIN to uppercase before saving to the database.
     */
    protected function vin(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper($value),
        );
    }

    /**
     * Always set the Registration Number to uppercase before saving to the database.
     */
    protected function registrationNumber(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper($value),
        );
    }


    // --- RELATIONSHIPS ---

    /**
     * Get the purchase record associated with the car.
     */
    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'car_id', 'id');
    }

    /**
     * Get the sale record for the car (the car that was sold).
     */
    public function sale()
    {
        return $this->hasOne(Sale::class, 'car_id');
    }

    /**
     * Get the sale record where this car was used as a part-exchange.
     */
    public function partExchangedInSale()
    {
        return $this->hasOne(Sale::class, 'part_exchange_car_id');
    }

    /**
     * Get all of the work records for the car.
     */
    public function workDone()
    {
        return $this->hasMany(WorkDone::class, 'car_id')->orderBy('work_date', 'desc');
    }
}
