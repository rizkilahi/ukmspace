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
Route::middleware(['auth', 'isUKM'])->group(function () {
    Route::get('/ukm/events', [EventController::class, 'index'])->name('ukm.events.index');
    Route::get('/ukm/events/manage', [EventController::class, 'manage'])->name('ukm.events.manage');
    Route::get('/ukm/events/create', [EventController::class, 'create'])->name('ukm.events.create');
    Route::post('/ukm/events', [EventController::class, 'store'])->name('ukm.events.store');
    Route::get('/ukm/events/{event}/edit', [EventController::class, 'edit'])->name('ukm.events.edit');
    Route::put('/ukm/events/{event}', [EventController::class, 'update'])->name('ukm.events.update');
    Route::delete('/ukm/events/{event}', [EventController::class, 'destroy'])->name('ukm.events.destroy');
    Route::get('/ukm/profile', [UKMController::class, 'index'])->name('ukms.profile');
    Route::get('/ukm/profile/edit', [UKMController::class, 'edit'])->name('ukms.profile.edit');
    Route::patch('/ukm/profile/update', [UKMController::class, 'update'])->name('ukms.profile.update');
    // Route::put('/ukm/profile/update', [UKMController::class, 'update'])->name('ukms.profile.update');
});

// Rute untuk Admin (Hanya untuk User dengan Role Admin)
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ukms', UKMController::class);
    Route::resource('users', UserController::class);
});

// Tambahkan rute otentikasi bawaan Breeze
require __DIR__ . '/auth.php';
