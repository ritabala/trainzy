<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ClearsUserCache;
use Illuminate\Support\Facades\Cache;
class FortifyServiceProvider extends ServiceProvider
{
    use ClearsUserCache;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Cache roles & permissions on login
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::withoutGlobalScopes()->where('email', $request->email)->first();

            cache()->forget('user');
            cache()->forget('gym');
            cache()->forget('global_settings');
            cache()->forget('currency');
            
            if (!$user || !Hash::check($request->password, $user->password)) {
                return null;
            }

            if (!$user->is_active) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Your account is inactive. Please contact the administrator.'],
                ]);
            }

            if(!is_null($user->gym_id)){
                $this->clearUserCache($user);
                $user->cacheRolesPermissions(); // Cache after login
            }

            return $user;
        });
    }
}
