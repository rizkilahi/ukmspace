<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UKMController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rute Halaman Utama (Public)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Dashboard (Khusus Pengguna yang Login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rute Profil Pengguna (Hanya untuk Pengguna Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk UKM (Hanya untuk User dengan Role UKM)
Route::middleware(['auth', 'isUKM'])->prefix('ukm')->name('ukm.')->group(function () {
    Route::resource('events', EventController::class);
    Route::resource('ukms', UKMController::class);
});

// Rute untuk Admin (Hanya untuk User dengan Role Admin)
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ukms', UKMController::class);
    Route::resource('users', UserController::class);
});

// Rute Halaman Lainnya (Public)
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Tambahkan rute otentikasi bawaan Breeze
require __DIR__ . '/auth.php';
