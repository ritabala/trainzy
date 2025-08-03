<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Traits\BelongsToGym;

class StaffDetail extends Model
{
    use HasFactory, BelongsToGym;

    protected $fillable = [
        'gym_id',
        'user_id',
        'staff_type_id',
        'date_of_joining',
        'blood_group',
        'specialization',
        'certifications',
        'medical_history'
    ];

    protected $casts = [
        'date_of_joining' => 'date',
        'certifications' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staffType(): BelongsTo
    {
        return $this->belongsTo(StaffType::class);
    }
} 