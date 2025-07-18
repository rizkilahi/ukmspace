<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UKMController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rute Halaman Utama (Public)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/ukms', [UKMController::class, 'indexEvent'])->name('ukms');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::get('/{event}/ukm/isevents', [UKMController::class, 'show'])->name('ukmIs.events');

// Rute Dashboard (Khusus Pengguna yang Login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rute Profil Pengguna (Hanya untuk Pengguna Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

Route::middleware(['auth', 'isUser'])->group(function () {
    Route::post('/events/{event}', [EventController::class, 'register'])->name('events.register');

});

// Rute untuk UKM (Hanya untuk User dengan Role UKM)
Route::middleware(['auth', 'isUKM'])->prefix('ukm')->name('ukm.')->group(function () {
    Route::resource('events', EventController::class);
});

// Rute UKM Profile (accessible by UKM role)
Route::middleware(['auth', 'isUKM'])->group(function () {
    Route::get('/ukms/profile', [UKMController::class, 'index'])->name('ukms.profile');
    Route::get('/ukms/profile/edit', [UKMController::class, 'edit'])->name('ukms.profile.edit');
    Route::put('/ukms/profile', [UKMController::class, 'update'])->name('ukms.profile.update');
});

// Rute untuk Admin (Hanya untuk User dengan Role Admin)
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ukms', UKMController::class);
    Route::resource('users', UserController::class);
});

// Tambahkan rute otentikasi bawaan Breeze
require __DIR__ . '/auth.php';
