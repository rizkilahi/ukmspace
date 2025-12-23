<?php

/**
 * Test script for QR code attendance system
 * Run: php test_qr_attendance.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;

echo "=== QR Code Attendance System Test ===\n\n";

// Find an event with accepted registrations
$event = Event::whereHas('registrations', function($query) {
    $query->where('status', 'accepted');
})->with(['registrations' => function($query) {
    $query->where('status', 'accepted')->with('user');
}])->first();

if (!$event) {
    echo "❌ No events with accepted registrations found.\n";
    echo "Please accept some registrations first.\n";
    exit(1);
}

echo "📅 Event: {$event->title}\n";
echo "📍 Location: {$event->location}\n";
echo "📆 Date: {$event->event_date->format('M d, Y g:i A')}\n\n";

// Generate check-in token
$token = hash('sha256', config('app.key') . $event->id);
$checkInUrl = url("/events/{$event->id}/check-in/{$token}");

echo "🔗 Check-in URL:\n{$checkInUrl}\n\n";

echo "🎫 QR Code URL (Google Charts API):\n";
$qrUrl = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($checkInUrl);
echo "{$qrUrl}\n\n";

// Show accepted registrations
$acceptedRegistrations = $event->registrations;
echo "👥 Accepted Registrations: " . $acceptedRegistrations->count() . "\n";
echo str_repeat('-', 80) . "\n";

foreach ($acceptedRegistrations as $index => $registration) {
    $status = $registration->checked_in_at ? '✅ Checked In' : '⏳ Pending';
    $time = $registration->checked_in_at
        ? $registration->checked_in_at->format('g:i A, M d')
        : '-';
    $method = $registration->check_in_method ? "({$registration->check_in_method})" : '';

    echo sprintf(
        "%2d. %-30s %-20s %s %s\n",
        $index + 1,
        $registration->user->name,
        $registration->user->email,
        $status,
        $method
    );

    if ($registration->checked_in_at) {
        echo "    Check-in time: {$time}\n";
    }
}

echo str_repeat('-', 80) . "\n";

// Calculate statistics
$totalRegistrations = $acceptedRegistrations->count();
$checkedInCount = $acceptedRegistrations->whereNotNull('checked_in_at')->count();
$pendingCount = $totalRegistrations - $checkedInCount;
$attendanceRate = $totalRegistrations > 0 ? round(($checkedInCount / $totalRegistrations) * 100, 1) : 0;

echo "\n📊 Attendance Statistics:\n";
echo "Total Registrations: {$totalRegistrations}\n";
echo "Checked In: {$checkedInCount}\n";
echo "Pending: {$pendingCount}\n";
echo "Attendance Rate: {$attendanceRate}%\n\n";

// Test manual check-in simulation
echo "🧪 Testing Manual Check-In...\n";
$testRegistration = $acceptedRegistrations->whereNull('checked_in_at')->first();

if ($testRegistration) {
    echo "Checking in: {$testRegistration->user->name}...\n";

    $testRegistration->update([
        'checked_in_at' => now(),
        'check_in_method' => 'manual'
    ]);

    echo "✅ Successfully checked in at " . $testRegistration->checked_in_at->format('g:i A') . "\n";

    // Verify the update
    $testRegistration->refresh();
    if ($testRegistration->checked_in_at) {
        echo "✅ Verification: Check-in recorded in database\n";
    } else {
        echo "❌ Verification: Check-in NOT recorded in database\n";
    }
} else {
    echo "ℹ️  No pending registrations to test check-in\n";
}

echo "\n=== Test Completed ===\n";
echo "\n💡 Next Steps:\n";
echo "1. Visit: " . url("/ukm/events/{$event->id}/qr-code") . "\n";
echo "2. View the QR code and attendance list\n";
echo "3. Test QR code scanning (requires login)\n";
echo "4. Try manual check-in/out from attendance list\n";
