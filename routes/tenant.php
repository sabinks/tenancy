<?php

declare(strict_types=1);

use Carbon\Carbon;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Route::get('/', function () {
    //     return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('domain');
    // });
    Route::prefix('/tenant-api')->group(function () {
        Route::get('/', function () {
            $tenant = Tenant::find(tenant('id'));
            return 'This is your multi-tenant application. The id of the current tenant is ' . $tenant;
        });
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/get-user', [UserController::class, 'getUser']);
            Route::resource('/posts', PostController::class);

            Route::get('role-list', [RoleController::class, 'roleList']);
            Route::resource('role', RoleController::class);
            Route::resource('permission', PermissionController::class);

            Route::post('assign-role', [UserController::class, 'assignRole']);
            Route::post('revoke-role', [UserController::class, 'revokeRole']);
            Route::post('user-roles', [UserController::class, 'userRoles']);

            Route::post('assign-permission', [RolePermissionController::class, 'assignPermission']);
            Route::post('revoke-permission', [RolePermissionController::class, 'revokePermission']);
            Route::post('role-permissions', [RolePermissionController::class, 'rolePermissions']);
        });

        Route::get('/create-user', function () {
            $tenant = Tenant::find(tenant('id'));
            $tenant->run(function () {
                $userCount = User::count();
                User::create([
                    'name' => Str::of('user ')->append($userCount + 1),
                    'email' => Str::of('user')->append($userCount + 1)->append('@mail.com'),
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make('P@ss1234'),
                    'remember_token' => '',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            });
            return tenant('id');
        });
    });
});
