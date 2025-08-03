<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait InvalidatesUserSession
{
    /**
     * Invalidate all sessions for the given user
     * 
     * @return bool Returns true if sessions were successfully invalidated, false otherwise
     */
    private function invalidateUserSessions(?User $user): bool
    {
        if (!$user) {
            return true; // No sessions to invalidate for null user
        }

        try {
            // Delete all sessions for the user
            DB::table('sessions')->where('user_id', $user->id)->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to invalidate sessions for user ' . $user->id, [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return false;
        }
    }
} 