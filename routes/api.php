<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\LoginController as UserLoginController;
use App\Http\Controllers\Api\V1\Admin\LoginController as AdminLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('api/v1/admin')
    ->name('api.admin.')
    ->group(
        function (): void {
            Route::post('/login', AdminLoginController::class)->name('login');
        }
    );

Route::prefix('api/v1/user')
    ->name('api.user.')
    ->group(
        function (): void {
            Route::post('/login', UserLoginController::class)->name('login');
        }
    );
