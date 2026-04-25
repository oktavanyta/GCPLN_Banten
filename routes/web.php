<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroundcheckController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DashboardController;
use App\Models\Up3;
use App\Models\Ulp;

use App\Http\Controllers\Auth\LoginController;

Route::get('/', [DashboardController::class, 'index']);

//Export routes
Route::get('/export/prabayar', [ExportController::class, 'prabayar'])->name('export.prabayar');
Route::get('/export/pascabayar', [ExportController::class, 'pascabayar'])->name('export.pascabayar'); 

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Only admin (auth) can access groundcheck
Route::middleware('auth')->group(function () {
    Route::resource('groundcheck', GroundcheckController::class);
    Route::get('/get-up3/{upi_id}', function ($upi_id) {
        return Up3::where('upi_id', $upi_id)->get();
    });
    Route::get('/get-ulp/{up3_id}', function ($up3_id) {
        return Ulp::where('up3_id', $up3_id)->get();
    });

});