<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'notes',
    ];

    /**
     * Get the sales associated with the customer.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id', 'customer_id');
    }
}