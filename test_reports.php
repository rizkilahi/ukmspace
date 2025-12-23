<?php

/**
 * Test script for Advanced Reports system
 * Run: php test_reports.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\UKM;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Advanced Reports System Test ===\n\n";

// Get a UKM user
$ukmUser = User::where('role', 'ukm')->first();

if (!$ukmUser || !$ukmUser->ukm_id) {
    echo "❌ No UKM user found with ukm_id.\n";
    exit(1);
}

$ukmId = $ukmUser->ukm_id;
$ukm = UKM::find($ukmId);

echo "🏛️  UKM: {$ukm->name}\n";
echo "👤 Coordinator: {$ukmUser->name}\n\n";

// Get events for this UKM
$events = Event::where('ukm_id', $ukmId)->get();

echo "📊 REPORT METRICS\n";
echo str_repeat('=', 80) . "\n\n";

// Overall Statistics
$totalEvents = $events->count();
$upcomingEvents = $events->where('event_date', '>', now())->count();
$completedEvents = $events->where('event_date', '<', now())->count();

echo "📅 Event Summary:\n";
echo "   Total Events: {$totalEvents}\n";
echo "   Upcoming: {$upcomingEvents}\n";
echo "   Completed: {$completedEvents}\n\n";

// Registration Statistics
$totalRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId) {
    $query->where('ukm_id', $ukmId);
})->count();

$acceptedRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId) {
    $query->where('ukm_id', $ukmId);
})->where('status', 'accepted')->count();

$pendingRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId) {
    $query->where('ukm_id', $ukmId);
})->where('status', 'pending')->count();

$rejectedRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId) {
    $query->where('ukm_id', $ukmId);
})->where('status', 'rejected')->count();

$attendedCount = EventRegistration::whereHas('event', function($query) use ($ukmId) {
    $query->where('ukm_id', $ukmId);
})->whereNotNull('checked_in_at')->count();

echo "👥 Registration Summary:\n";
echo "   Total: {$totalRegistrations}\n";
echo "   Accepted: {$acceptedRegistrations}\n";
echo "   Pending: {$pendingRegistrations}\n";
echo "   Rejected: {$rejectedRegistrations}\n";
echo "   Attended: {$attendedCount}\n\n";

// Rates
$acceptanceRate = $totalRegistrations > 0 ? round(($acceptedRegistrations / $totalRegistrations) * 100, 1) : 0;
$attendanceRate = $acceptedRegistrations > 0 ? round(($attendedCount / $acceptedRegistrations) * 100, 1) : 0;
$avgRegPerEvent = $totalEvents > 0 ? round($totalRegistrations / $totalEvents, 1) : 0;

echo "📈 Performance Rates:\n";
echo "   Acceptance Rate: {$acceptanceRate}%\n";
echo "   Attendance Rate: {$attendanceRate}%\n";
echo "   Avg Registrations/Event: {$avgRegPerEvent}\n\n";

// Top Performing Events
echo "🏆 TOP PERFORMING EVENTS\n";
echo str_repeat('-', 80) . "\n";

$topEvents = Event::where('ukm_id', $ukmId)
    ->withCount([
        'registrations',
        'registrations as accepted_count' => function($query) {
            $query->where('status', 'accepted');
        },
        'registrations as attended_count' => function($query) {
            $query->whereNotNull('checked_in_at');
        }
    ])
    ->orderBy('registrations_count', 'desc')
    ->take(5)
    ->get();

foreach($topEvents as $index => $event) {
    $eventAttendanceRate = $event->accepted_count > 0
        ? round(($event->attended_count / $event->accepted_count) * 100, 1)
        : 0;

    echo sprintf(
        "%d. %-40s | Reg: %3d | Accepted: %3d | Attended: %3d | Rate: %5.1f%%\n",
        $index + 1,
        substr($event->title, 0, 40),
        $event->registrations_count,
        $event->accepted_count,
        $event->attended_count,
        $eventAttendanceRate
    );
}

echo "\n";

// Event-by-Event Analysis
if ($events->count() >= 2) {
    echo "📋 EVENT COMPARISON\n";
    echo str_repeat('-', 80) . "\n";

    $comparisonEvents = $events->take(3);

    foreach($comparisonEvents as $event) {
        $eventRegistrations = $event->registrations()->count();
        $eventAccepted = $event->registrations()->where('status', 'accepted')->count();
        $eventAttended = $event->registrations()->whereNotNull('checked_in_at')->count();
        $eventNoShow = $eventAccepted - $eventAttended;

        $eventAcceptanceRate = $eventRegistrations > 0
            ? round(($eventAccepted / $eventRegistrations) * 100, 1)
            : 0;
        $eventAttendanceRate = $eventAccepted > 0
            ? round(($eventAttended / $eventAccepted) * 100, 1)
            : 0;

        echo "\n{$event->title}\n";
        echo "   Date: " . $event->event_date->format('M d, Y') . "\n";
        echo "   Registrations: {$eventRegistrations} | Accepted: {$eventAccepted} | Attended: {$eventAttended} | No-Show: {$eventNoShow}\n";
        echo "   Acceptance: {$eventAcceptanceRate}% | Attendance: {$eventAttendanceRate}%\n";

        // Insights
        $insights = [];
        if ($eventAcceptanceRate >= 80) $insights[] = "✅ High acceptance";
        if ($eventAttendanceRate >= 80) $insights[] = "✅ Excellent attendance";
        if ($eventAcceptanceRate < 50) $insights[] = "⚠️  Low acceptance";
        if ($eventAttendanceRate < 50) $insights[] = "⚠️  Poor attendance";

        if (!empty($insights)) {
            echo "   " . implode(" | ", $insights) . "\n";
        }
    }

    echo "\n";
}

// Monthly Trends
echo "📅 MONTHLY TRENDS (Last 6 Months)\n";
echo str_repeat('-', 80) . "\n";

$monthlyData = Event::where('ukm_id', $ukmId)
    ->where('event_date', '>=', now()->subMonths(6))
    ->selectRaw('DATE_FORMAT(event_date, "%Y-%m") as month, COUNT(*) as count')
    ->groupBy('month')
    ->orderBy('month')
    ->get();

if ($monthlyData->isEmpty()) {
    echo "   No events in the last 6 months\n\n";
} else {
    foreach($monthlyData as $data) {
        $monthName = \Carbon\Carbon::createFromFormat('Y-m', $data->month)->format('M Y');
        echo "   {$monthName}: {$data->count} events\n";
    }
    echo "\n";
}

// Recommendations
echo "💡 RECOMMENDATIONS\n";
echo str_repeat('=', 80) . "\n";

if ($acceptanceRate < 70) {
    echo "• Consider reviewing registration acceptance criteria\n";
}

if ($attendanceRate < 70) {
    echo "• Send reminder notifications to registered participants\n";
    echo "• Consider implementing a confirmation system\n";
}

if ($avgRegPerEvent < 20) {
    echo "• Improve event promotion and marketing\n";
    echo "• Engage more with target audience\n";
}

if ($acceptanceRate >= 80 && $attendanceRate >= 80) {
    echo "✅ Excellent performance! Keep up the good work!\n";
}

echo "\n=== Test Completed ===\n";
echo "\n🌐 Access Reports:\n";
echo "1. Main Dashboard: " . url("/ukm/reports") . "\n";
echo "2. Event Report: " . url("/ukm/reports/events/{event_id}") . "\n";
echo "3. Compare Events: " . url("/ukm/reports/compare") . "\n";
