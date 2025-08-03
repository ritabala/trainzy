<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\BelongsToGym;

class StaffType extends Model
{
    use HasFactory, BelongsToGym;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($staffType) {
            // Update all related staff details to set staff_type_id to null
            StaffDetail::where('staff_type_id', $staffType->id)
                ->update(['staff_type_id' => null]);
        });
    }

    /**
     * Get the staff members associated with this staff type.
     */
    public function staffMembers(): HasMany
    {
        return $this->hasMany(User::class, 'staff_type_id')
            ->role('staff-' . gym()->id);
    }
} 