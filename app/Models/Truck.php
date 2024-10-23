<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $fillable = [
        'unit_number',
        'year',
        'notes'
    ];

    public function subunits(): HasMany
    {
        return $this->hasMany(TruckSubunit::class, 'main_truck_id');
    }

    public function assignedAsSubunit(): HasMany
    {
        return $this->hasMany(TruckSubunit::class, 'subunit_truck_id');
    }
}