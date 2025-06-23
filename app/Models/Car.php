<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function vin(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper($value),
        );
    }

    protected function registrationNumber(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::upper($value),
        );
    }

    // --- RELATIONSHIPS ---

    public function purchase()
    {
        // Correctly defines the one-to-one relationship with the Purchase model.
        return $this->hasOne(Purchase::class, 'car_id', 'id');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'car_id');
    }

    public function partExchangedInSale()
    {
        return $this->hasOne(Sale::class, 'part_exchange_car_id');
    }

    public function workDone()
    {
        return $this->hasMany(WorkDone::class, 'car_id')->orderBy('work_date', 'desc');
    }

    public function documents()
    {
        return $this->hasMany(CarDocument::class, 'car_id');
    }

    /**
     * Get all of the images for the car.
     */
    public function images()
    {
        return $this->hasMany(CarImage::class, 'car_id');
    }

    /**
     * Get the primary image for the car.
     */
    public function primaryImage()
    {
        // This defines a one-to-one relationship to get only the primary image.
        return $this->hasOne(CarImage::class, 'car_id')->where('is_primary', true);
    }

    // --- PROFITABILITY CALCULATIONS (ACCESSORS) ---

    protected function totalWorkDoneCost(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->workDone()->sum('cost')
        );
    }
    
    protected function totalCost(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Return 0 if there is no purchase record.
                if (!$this->purchase) {
                    return 0;
                }

                // Sum all costs from the purchase record. Null values are treated as 0.
                $purchaseCosts = ($this->purchase->purchase_price ?? 0)
                               + ($this->purchase->vrt_paid_amount ?? 0)
                               + ($this->purchase->nct_cost ?? 0)
                               + ($this->purchase->import_duty_amount ?? 0)
                               + ($this->purchase->transport_cost_import ?? 0)
                               + ($this->purchase->other_expenses_total ?? 0);

                return $purchaseCosts + $this->totalWorkDoneCost;
            }
        );
    }

    protected function profitMargin(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Return 0 if there is no sale record.
                if (!$this->sale) {
                    return 0;
                }
                
                $netSalePrice = ($this->sale->selling_price ?? 0) - ($this->sale->part_exchange_value ?? 0);
                
                return $netSalePrice - $this->totalCost;
            }
        );
    }
}
