<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $primaryKey = 'supplier_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'notes',
    ];

    /**
     * Get the purchases associated with the supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id', 'supplier_id');
    }
}