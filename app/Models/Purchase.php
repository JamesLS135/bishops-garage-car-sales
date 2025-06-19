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
        'supplier_name',
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
    ];

    /**
     * Get the car that this purchase belongs to.
     */
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
