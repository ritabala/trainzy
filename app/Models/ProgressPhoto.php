<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToGym;

class ProgressPhoto extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'user_id',
        'body_measurement_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'title',
        'description',
        'view_type',
        'photo_date',
    ];

    protected $casts = [
        'photo_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bodyMeasurement(): BelongsTo
    {
        return $this->belongsTo(BodyMeasurement::class);
    }
} 