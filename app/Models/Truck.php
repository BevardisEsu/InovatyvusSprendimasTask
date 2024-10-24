<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Truck extends Model
{
    use LogsActivity, HasFactory;

    protected $fillable = [
        'unit_number',
        'year',
        'notes'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'unit_number', 
                'year', 
                'notes'
            ])
            // Only log subunit relations when they exist
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
                'created' => "Truck {$this->unit_number} was created",
                'updated' => "Truck {$this->unit_number} was updated",
                'deleted' => "Truck {$this->unit_number} was deleted",
                default => $eventName
            })
            ->useLogName('truck');
    }

    public function subunits(): HasMany
    {
        return $this->hasMany(TruckSubunit::class, 'main_truck_id');
    }

    public function assignedAsSubunit(): HasMany
    {
        return $this->hasMany(TruckSubunit::class, 'subunit_truck_id');
    }
}