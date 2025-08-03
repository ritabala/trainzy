<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToGym;

class BodyMeasurementValue extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'body_measurement_id',
        'body_metric_type_id',
        'value',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'float',
    ];

    /**
     * Get the body measurement this value belongs to.
     */
    public function bodyMeasurement(): BelongsTo
    {
        return $this->belongsTo(BodyMeasurement::class);
    }

    /**
     * Get the metric type for this measurement value.
     */
    public function metricType(): BelongsTo
    {
        return $this->belongsTo(BodyMetricType::class, 'body_metric_type_id');
    }
}
