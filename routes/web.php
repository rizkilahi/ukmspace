<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UKMController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rute untuk halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute dashboard (hanya untuk pengguna yang sudah login dan terverifikasi)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'isUser'])->name('dashboard');

// Rute untuk profile pengguna (bawaan Breeze)
Route::middleware(['auth', 'isUser'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk fitur CRUD
Route::middleware(['auth', 'isUKM'])->group(function () {
    // Rute untuk Events (hanya untuk UKM)
    Route::resource('events', EventController::class);
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    // Rute untuk UKMs (hanya untuk Admin)
    Route::resource('ukms', UKMController::class);

    // Rute untuk Users (hanya untuk Admin)
    Route::resource('users', UserController::class);
});

// Tambahkan rute otentikasi bawaan Breeze
require __DIR__.'/auth.php';
