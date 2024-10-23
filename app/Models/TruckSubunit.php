<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckSubunit extends Model
{
    protected $fillable = [
        'main_truck_id',
        'subunit_truck_id',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function mainTruck(): BelongsTo
    {
        return $this->belongsTo(Truck::class, 'main_truck_id');
    }

    public function subunitTruck(): BelongsTo
    {
        return $this->belongsTo(Truck::class, 'subunit_truck_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}