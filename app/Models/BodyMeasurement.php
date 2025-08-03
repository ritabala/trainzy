<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class BodyMeasurement extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'measurement_date',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'measurement_date' => 'datetime',
    ];

    /**
     * Get the user that owns the measurement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the measurement values for this body measurement.
     */
    public function measurementValues(): HasMany
    {
        return $this->hasMany(BodyMeasurementValue::class);
    }

    /**
     * Get a specific measurement value by metric type slug.
     *
     * @param string $metricSlug
     * @return float|null
     */
    public function getMetricValue(string $metricSlug): ?float
    {
        $metricType = BodyMetricType::where('slug', $metricSlug)->first();
        
        if (!$metricType) {
            return null;
        }

        $value = $this->measurementValues()
            ->where('body_metric_type_id', $metricType->id)
            ->first();

        return $value ? $value->value : null;
    }
}
