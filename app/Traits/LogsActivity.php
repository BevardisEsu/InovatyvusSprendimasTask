<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait LogsActivity
{
    protected static $loggingActivity = false;

    public static function bootLogsActivity()
    {
        if (get_called_class() === ActivityLog::class) {
            return;
        }

        static::created(function ($model) {
            self::logActivitySafely('created', $model, $model->getAttributes());
        });

        static::updated(function ($model) {
            self::logActivitySafely('updated', $model, $model->getDirty());
        });

        static::deleted(function ($model) {
            self::logActivitySafely('deleted', $model, $model->getAttributes());
        });
    }

    protected static function logActivitySafely($event, $model, $properties)
    {
        if (self::$loggingActivity) {
            return;
        }

        self::$loggingActivity = true;

        try {
            $user = auth()->user();
            $filteredProperties = self::filterSensitiveProperties($properties);
            $properties = json_encode($filteredProperties);

            $description = match($event) {
                'created' => "Created new " . strtolower(class_basename($model)) . " #{$model->id}",
                'updated' => "Updated " . strtolower(class_basename($model)) . " #{$model->id}",
                'deleted' => "Deleted " . strtolower(class_basename($model)) . " #{$model->id}",
                default => $event
            };

            ActivityLog::withoutEvents(function () use ($event, $model, $user, $properties, $description) {
                $recentLog = self::getRecentLog($model);

                if ($recentLog) {
                    $recentLog->description .= ", {$description}";
                    $recentLog->properties = self::mergeProperties($recentLog->properties, $properties);
                    $recentLog->save();
                } else {
                    ActivityLog::create([
                        'log_name' => class_basename($model),
                        'description' => $description,
                        'subject_type' => get_class($model),
                        'subject_id' => $model->id,
                        'user_id' => $user ? $user->id : null,
                        'user_name' => $user ? $user->name . ' ' . $user->last_name : null,
                        'properties' => $properties,
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage(), [
                'event' => $event,
                'model' => get_class($model),
                'model_id' => $model->id,
            ]);
        } finally {
            self::$loggingActivity = false;
        }
    }

    protected static function getRecentLog($model)
    {
        $oneSecondAgo = Carbon::now()->subSecond();
        return ActivityLog::where('subject_type', get_class($model))
            ->where('subject_id', $model->id)
            ->where('created_at', '>=', $oneSecondAgo)
            ->latest()
            ->first();
    }

    protected static function mergeProperties($existing, $new)
    {
        $existingArray = json_decode($existing, true) ?: [];
        $newArray = json_decode($new, true) ?: [];
        $mergedArray = array_merge($existingArray, $newArray);
        $filteredArray = self::filterSensitiveProperties($mergedArray);
        return json_encode($filteredArray);
    }

    protected static function filterSensitiveProperties($properties)
    {
        $filtered = [];
        foreach ($properties as $key => $value) {
            $lowercaseKey = strtolower($key);
            $lowercaseValue = is_string($value) ? strtolower($value) : '';
            if (strpos($lowercaseKey, 'token') === false && strpos($lowercaseValue, 'token') === false) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }
}