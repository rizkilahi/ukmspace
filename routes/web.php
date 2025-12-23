<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UKMController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UKMController as AdminUKMController;
use Illuminate\Support\Facades\Route;

// Rute Halaman Utama (Public)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/calendar', [HomeController::class, 'calendar'])->name('calendar');
Route::get('/api/calendar-events', [HomeController::class, 'calendarEvents'])->name('api.calendar.events');
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
    Route::get('/my-events', [ProfileController::class, 'myEvents'])->name('profile.myEvents');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

Route::middleware(['auth', 'isUser'])->group(function () {
    Route::post('/events/{event}', [EventController::class, 'register'])
        ->name('events.register')
        ->middleware('throttle:10,1'); // Rate limit: 10 registrations per minute
});

// Rute untuk UKM (Hanya untuk User dengan Role UKM)
Route::middleware(['auth', 'isUKM'])->prefix('ukm')->name('ukm.')->group(function () {
    Route::resource('events', EventController::class);
    Route::get('events/{event}/registrations', [EventController::class, 'registrations'])->name('events.registrations');
    Route::get('events/{event}/export', [EventController::class, 'exportRegistrations'])->name('events.export');
    Route::patch('registrations/{registration}/status', [EventController::class, 'updateRegistrationStatus'])->name('registrations.updateStatus');
    Route::get('analytics', [EventController::class, 'analytics'])->name('analytics');

    // QR Code routes
    Route::get('events/{event}/qr-code', [EventController::class, 'showQRCode'])->name('events.qr-code');
    Route::post('events/{event}/registrations/{registration}/manual-checkin', [EventController::class, 'manualCheckIn'])->name('events.manual-checkin');

    // Reports routes
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/events/{event}', [ReportController::class, 'eventReport'])->name('reports.event');
    Route::get('reports/compare', [ReportController::class, 'compare'])->name('reports.compare');
    
    // UKM Profile routes
    Route::get('profile', [UKMController::class, 'index'])->name('profile');
    Route::get('profile/edit', [UKMController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [UKMController::class, 'update'])->name('profile.update');
});

// Rute untuk Admin (Hanya untuk User dengan Role Admin)
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('ukms', AdminUKMController::class);
    Route::resource('users', AdminUserController::class);
});

// Tambahkan rute otentikasi bawaan Breeze
require __DIR__ . '/auth.php';
