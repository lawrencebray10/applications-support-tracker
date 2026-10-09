<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

        
    Route::get('/activities', [ActivityController::class, 'index'])
        ->name('activities.index');


    Route::get('/activities/{activity}/update', [ActivityLogController::class, 'create'])
        ->name('activity-logs.create');

    Route::post('/activities/{activity}/update', [ActivityLogController::class, 'store'])
        ->name('activity-logs.store');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/activities/create', [ActivityController::class, 'create'])
            ->name('activities.create');
            
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::post('/activities', [ActivityController::class, 'store'])
            ->name('activities.store');

        Route::patch('/activities/{activity}/deactivate', [ActivityController::class, 'deactivate'])
            ->name('activities.deactivate');
    });
});