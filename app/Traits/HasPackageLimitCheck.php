<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Staff;
use App\Models\ActivityClass;
use App\Models\GymPackageSubscription;

trait HasPackageLimitCheck
{
    public function mount()
    {
        $this->memberRole = 'member-' . gym()->id;
        $this->staffRole = ['staff-' . gym()->id, 'admin-' . gym()->id];
    }

    private const RESOURCE_TYPES = [
        'members' => [
            'model' => User::class,
            'limit_field' => 'max_members',
            'translation_key' => 'members.max_members_limit_reached'
        ],
        'staff' => [
            'model' => User::class,
            'limit_field' => 'max_staff',
            'translation_key' => 'staff.max_staff_limit_reached'
        ],
        'classes' => [
            'model' => ActivityClass::class,
            'limit_field' => 'max_classes',
            'translation_key' => 'activity.max_classes_limit_reached'
        ]
    ];

    /**
     * Get the current gym's active package subscription
     */
    private function getActiveSubscription(): ?GymPackageSubscription
    {
        $gym = gym();
        return $gym ? $gym->activePackageSubscription() : null;
    }

    /**
     * Get the role configuration for a resource type
     */
    private function getRoleConfig(string $resourceType): string|array|null
    {
        return match($resourceType) {
            'members' => $this->memberRole,
            'staff' => $this->staffRole,
            default => null
        };
    }

    /**
     * Check if the current gym has reached its package limit for a specific resource
     *
     * @param string $resourceType The type of resource to check ('members', 'staff', 'classes')
     * @param int|null $excludeId ID to exclude from the count (useful when editing existing records)
     * @return bool True if the limit hasn't been reached, false otherwise
     */
    protected function checkLimit(string $resourceType, ?int $excludeId = null): bool
    {
        $maxLimit = $this->getMaxResourceLimit($resourceType);
        if ($maxLimit === null) {
            return true; // No limit set
        }

        $currentCount = $this->getCurrentResourceCount($resourceType, $excludeId);
        return $currentCount < $maxLimit;
    }

    /**
     * Check if the current resource creation is allowed based on package limits
     *
     * @param string $resourceType The type of resource to check ('members', 'staff', 'classes')
     * @param int|null $excludeId ID to exclude from the count (useful when editing existing records)
     * @param bool $isEdit Whether this is an edit operation
     * @return bool True if the operation is allowed, false otherwise
     */
    protected function canCreateResource(string $resourceType, ?int $excludeId = null, bool $isEdit = false): bool
    {
        // Skip limit check for edit operations
        if ($isEdit) {
            return true;
        }

        if (!$this->checkLimit($resourceType, $excludeId)) {
            session()->flash('error', $this->getLimitReachedMessage($resourceType));
            return false;
        }

        return true;
    }

    /**
     * Get the maximum allowed limit for a resource type
     */
    protected function getMaxResourceLimit(string $resourceType): ?int
    {
        if (!isset(self::RESOURCE_TYPES[$resourceType])) {
            throw new \InvalidArgumentException("Invalid resource type: {$resourceType}");
        }

        $subscription = $this->getActiveSubscription();
        if (!$subscription || !$subscription->package) {
            return null;
        }

        $limitField = self::RESOURCE_TYPES[$resourceType]['limit_field'];
        return $subscription->package->$limitField;
    }

    /**
     * Get the current count of a resource type
     */
    protected function getCurrentResourceCount(string $resourceType, ?int $excludeId = null): int
    {
        if (!isset(self::RESOURCE_TYPES[$resourceType])) {
            throw new \InvalidArgumentException("Invalid resource type: {$resourceType}");
        }

        $gym = gym();
        if (!$gym) {
            return 0;
        }

        $config = self::RESOURCE_TYPES[$resourceType];
        $query = $config['model']::where('gym_id', $gym->id);

        // Add role condition if specified
        $role = $this->getRoleConfig($resourceType);
        if ($role !== null) {
            $query->whereHas('roles', function($query) use ($role) {
                if (is_array($role)) {
                    $query->whereIn('name', $role);
                } else {
                    $query->where('name', $role);
                }
            });
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count();
    }

    /**
     * Get the error message for when a limit is reached
     */
    protected function getLimitReachedMessage(string $resourceType): string
    {
        if (!isset(self::RESOURCE_TYPES[$resourceType])) {
            throw new \InvalidArgumentException("Invalid resource type: {$resourceType}");
        }

        return __(self::RESOURCE_TYPES[$resourceType]['translation_key']);
    }

    /**
     * Get the remaining count for a resource type
     */
    protected function getRemainingResourceCount(string $resourceType, ?int $excludeId = null): ?int
    {
        $maxLimit = $this->getMaxResourceLimit($resourceType);
        if ($maxLimit === null) {
            return null; // No limit set
        }

        $currentCount = $this->getCurrentResourceCount($resourceType, $excludeId);
        return max(0, $maxLimit - $currentCount);
    }

    /**
     * Get the usage percentage for a resource type
     */
    protected function getResourceUsagePercentage(string $resourceType, ?int $excludeId = null): ?float
    {
        $maxLimit = $this->getMaxResourceLimit($resourceType);
        if ($maxLimit === null) {
            return null; // No limit set
        }

        $currentCount = $this->getCurrentResourceCount($resourceType, $excludeId);
        return ($currentCount / $maxLimit) * 100;
    }
} 