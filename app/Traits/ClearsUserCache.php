<?php

// app/Traits/ClearsUserCache.php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

trait ClearsUserCache
{
    /**
     * Clear user's cached roles and permissions
     */
    private function clearUserCache(?User $user): void
    {
        if ($user) {
            Cache::forget('user_roles_' . $user->id);
            Cache::forget('user_permissions_' . $user->id);
        }
    }
}