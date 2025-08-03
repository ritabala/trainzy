<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;
use App\Traits\InvalidatesUserSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DeleteUser implements DeletesUsers
{
    use InvalidatesUserSession;

    /**
     * Delete the given user.
     * It is specifically for self-service account deletion - when users delete their own accounts.
     * 
     * @throws RuntimeException if session invalidation fails
     */
    public function delete(User $user): void
    {
        DB::beginTransaction();
        try {
            if (!$this->invalidateUserSessions($user)) {
                throw new RuntimeException('Failed to invalidate user sessions');
            }

            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
