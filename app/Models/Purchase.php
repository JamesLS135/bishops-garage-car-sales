<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $primaryKey = 'purchase_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_id',
        'purchase_date',
        'supplier_id',
        'purchase_price',
        'odometer_at_purchase',
        'purchase_invoice_reference',
        'is_imported_vehicle',
        'vrt_paid_amount',
        'vrt_payment_date',
        'nct_status',
        'nct_test_date',
        'nct_certificate_number',
        'nct_cost',
        'import_duty_amount',
        'transport_cost_import',
        'other_expenses_total',
        'purchase_notes',
        'original_registration_number', // <-- ADDED
        'registration_change_date',     // <-- ADDED
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This will automatically convert date fields from strings to Carbon date objects.
     *
     * @var array
     */
    protected $casts = [
        'purchase_date' => 'datetime',
        'vrt_payment_date' => 'datetime',
        'nct_test_date' => 'datetime',
        'is_imported_vehicle' => 'boolean',
        'registration_change_date' => 'date', // <-- ADDED
    ];

    /**
     * Get the car that this purchase belongs to.
     */
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    /**
     * Get the supplier for this purchase.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}