<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PjuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('map.index');
})->name('home');

Route::get('/monitoring-map', [MapController::class, 'index'])->name('map.index');
Route::get('/api/markers', [MapController::class, 'apiMarkers'])->name('api.markers');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('can:view_dashboard')
        ->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PJU CRUD
    Route::get('pju', [PjuController::class, 'index'])
        ->middleware('can:view_pju')
        ->name('pju.index');
    Route::get('pju/create', [PjuController::class, 'create'])
        ->middleware('can:create_pju')
        ->name('pju.create');
    Route::post('pju', [PjuController::class, 'store'])
        ->middleware('can:create_pju')
        ->name('pju.store');
    Route::get('pju/{pju}/edit', [PjuController::class, 'edit'])
        ->middleware('can:edit_pju')
        ->name('pju.edit');
    Route::put('pju/{pju}', [PjuController::class, 'update'])
        ->middleware('can:edit_pju')
        ->name('pju.update');
    Route::delete('pju/{pju}', [PjuController::class, 'destroy'])
        ->middleware('can:delete_pju')
        ->name('pju.destroy');
    Route::post('pju/{pju}/verify', [PjuController::class, 'verify'])
        ->middleware('can:verify_pju')
        ->name('pju.verify');

    // Verification Queue (Verifikator & Super Admin)
    Route::get('/verifikasi', [VerificationController::class, 'index'])
        ->middleware('can:verify_pju')
        ->name('verification.index');

    // User Management (Super Admin)
    Route::middleware(['can:manage_users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Audit Logs (Super Admin)
    Route::get('/logs', [LogController::class, 'index'])
        ->middleware('can:view_logs')
        ->name('logs.index');

    // Map View (admin side)
    Route::get('/map', [MapController::class, 'index'])
        ->middleware('can:view_pju')
        ->name('map');
});
