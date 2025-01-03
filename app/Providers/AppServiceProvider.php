<?php

namespace App\Providers;

use Laravel\Passport\Token;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshToken;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Superadmin')) {
                return true;
            } else {
                $roles = $user->roles;
                foreach ($roles as $key => $role) {
                    if ($role->hasPermissionTo($ability)) {
                        return true;
                    }
                }
                return false;
            }
        });
        Passport::$registersRoutes = false;
        Passport::loadKeysFrom(__DIR__ . '/../..');
        Passport::enablePasswordGrant();
        Route::group([
            'as' => 'passport.',
            'middleware' => [
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
            ], // Use tenancy initialization middleware of your choice
            'prefix' => config('passport.path', 'oauth'),
            'namespace' => 'Laravel\Passport\Http\Controllers',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . "/../../vendor/laravel/passport/src/../routes/web.php");
        });

        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::tokensExpireIn(now()->addHours(24));
        Passport::refreshTokensExpireIn(now()->addHours(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}
