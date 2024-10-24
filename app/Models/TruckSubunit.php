<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TruckSubunit extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'main_truck_id',
                'subunit_truck_id',
                'start_date',
                'end_date'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => match ($eventName) {
                'created' => "Subunit relationship created between trucks #{$this->main_truck_id} and #{$this->subunit_truck_id}",
                'updated' => "Subunit relationship updated between trucks #{$this->main_truck_id} and #{$this->subunit_truck_id}",
                'deleted' => "Subunit relationship deleted between trucks #{$this->main_truck_id} and #{$this->subunit_truck_id}",
                default => $eventName
            })
            ->useLogName('truck_subunit');
    }

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