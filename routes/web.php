<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PjuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PJU CRUD
    Route::resource('pju', PjuController::class);
    Route::post('pju/{pju}/verify', [PjuController::class, 'verify'])->name('pju.verify');

    // User Management (Super Admin only)
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Map View (admin side)
    Route::get('/map', [MapController::class, 'index'])->name('map');
});
