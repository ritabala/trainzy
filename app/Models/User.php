<?php

namespace App\Models;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Traits\BelongsToGym;
use App\Services\QrCodeService;

class User extends Authenticatable
{
    // use HasApiTokens;
    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use BelongsToGym;

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestMembership()
    {
        return $this->hasOne(UserMembership::class, 'id', 'latest_membership_id')
            ->with(UserMembership::defaultEagerLoad());
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function progressPhotos()
    {
        return $this->hasMany(ProgressPhoto::class);
    }

    public function role()
    {
        return $this->roles()->limit(1);
    }

    public function isAdmin()
    {
        return $this->hasRole('admin-' . $this->gym_id);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Check if the user is a staff member.
     */
    public function isStaff()
    {
        return $this->hasRole('staff-' . $this->gym_id);
    }

    /**
     * Check if the user is a member.
     */
    public function isMember()
    {
        return $this->hasRole('member-' . $this->gym_id);
    }

    /**
     * Get the available time slots where the user is an instructor.
     */
    public function availableTimeSlots(): HasMany
    {
        return $this->hasMany(AvailableTimeSlot::class, 'user_id');
    }

    /**
     * Get the member's enrollments in time slots.
     */
    public function memberEnrollments(): HasMany
    {
        return $this->hasMany(MemberEnrollment::class, 'user_id');
    }

    /**
     * Get the member's recurring sessions.
     */
    public function recurringSessions(): HasMany
    {
        return $this->hasMany(RecurringSession::class, 'user_id');
    }

    /**
     * Get the user's body measurements.
     */
    public function bodyMeasurements(): HasMany
    {
        return $this->hasMany(BodyMeasurement::class, 'user_id');
    }

    public function metricTargets()
    {
        return $this->hasMany(BodyMetricTarget::class);
    }

    public function getTargetValue(string $metricSlug): ?float
    {
        $target = $this->metricTargets()
            ->whereHas('metricType', function ($query) use ($metricSlug) {
                $query->where('slug', $metricSlug);
            })
            ->first();

        return $target ? $target->target_value : null;
    }

    public function getStartValue(string $metricSlug): ?float
    {
        $metricType = BodyMetricType::where('slug', $metricSlug)->first();
        
        if (!$metricType) {
            return null;
        }

        $firstMeasurement = $this->bodyMeasurements()
            ->with(['measurementValues' => function ($query) use ($metricType) {
                $query->where('body_metric_type_id', $metricType->id);
            }])
            ->orderBy('measurement_date', 'asc')
            ->first();

        if (!$firstMeasurement) {
            return null;
        }

        $value = $firstMeasurement->measurementValues->first();
        return $value ? $value->value : null;
    }

    public function setTargetValue(int $metricTypeId, ?float $value): void
    {
        if ($value === null) {
            $this->metricTargets()->where('body_metric_type_id', $metricTypeId)->delete();
        } else {
            $this->metricTargets()->updateOrCreate(
                ['body_metric_type_id' => $metricTypeId],
                ['target_value' => $value]
            );
        }
    }

    public function cacheRolesPermissions(): void
    {
        Cache::put("user_roles_{$this->id}", $this->getRoleNames(), now()->addDay());
        Cache::put("user_permissions_{$this->id}", $this->getAllPermissions()->pluck('name')->values(), now()->addDay());
    }

    public function getCachedPermissions()
    {
        return Cache::remember("user_permissions_{$this->id}", now()->addDay(), function () {
            $permissions = $this->getAllPermissions()->pluck('name');
            
            // Filter out super-admin permissions for non-super-admin users
            if (!$this->isSuperAdmin()) {
                $permissions = $permissions->filter(function ($permission) {
                    return !in_array($permission, self::getSuperAdminPermissionNames());
                });
            }
            
            return $permissions;
        });
    }

    public function getCachedRoles()
    {
        return Cache::remember("user_roles_{$this->id}", now()->addDay(), function () {
            return $this->getRoleNames();
        });
    }

    /**
     * Get the staff details associated with the user.
     */
    public function staffDetail(): HasOne
    {
        return $this->hasOne(StaffDetail::class);
    }

    public function receivedMessages()
    {
        return $this->hasMany(MessageRecipient::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getQrCodeAttribute()
    {
        // Generate a new scan code if it doesn't exist
        if (!$this->scan_code) {
            $this->scan_code = $this->id . '_' . time();
            $this->save();
        }
        
        // Create the full URL for attendance recording
        $attendanceUrl = route('attendance.qr-codes.record', ['token' => $this->scan_code]);
        
        $qrCode = new \Endroid\QrCode\QrCode(
            data: $attendanceUrl,
            size: 300,
            margin: 10,
            foregroundColor: new \Endroid\QrCode\Color\Color(0, 0, 0),
            backgroundColor: new \Endroid\QrCode\Color\Color(255, 255, 255)
        );

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);

        return $result->getString();
    }

    public function hasAttendanceToday()
    {
        return $this->attendances()->today()->exists();
    }

    public static function createUserWithRole($email, $name, $roleName, $gymId, $password = 'password123')
    {
        // Debugging output to see if the user exists before insertion
        $existingUser = User::withoutGlobalScopes()->where('email', $email)->first();

        if ($existingUser) {
            \Log::info("User with email {$email} already exists, skipping creation.");
        } else {

            // If user does not exist, create the user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'email_verified_at' => now(),
                'is_active' => 1,
                'gym_id' => $gymId,
            ]);

            // Assign the role to the user
            $user->assignRole($roleName);

            return $user;
            // Log the user creation
            \Log::info("User with email {$email} created and assigned role {$roleName}.");
        }
    }

}
