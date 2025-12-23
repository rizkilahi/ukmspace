<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show reports dashboard
     */
    public function index(Request $request)
    {
        $ukmId = Auth::user()->ukm_id;

        // Date range filter
        $startDate = $request->input('start_date', now()->subMonths(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Total events in date range
        $totalEvents = Event::where('ukm_id', $ukmId)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->count();

        // Events by status
        $upcomingEvents = Event::where('ukm_id', $ukmId)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->where('event_date', '>', now())
            ->count();

        $completedEvents = Event::where('ukm_id', $ukmId)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->where('event_date', '<', now())
            ->count();

        // Registration metrics
        $totalRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId, $startDate, $endDate) {
            $query->where('ukm_id', $ukmId)
                  ->whereBetween('event_date', [$startDate, $endDate]);
        })->count();

        $acceptedRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId, $startDate, $endDate) {
            $query->where('ukm_id', $ukmId)
                  ->whereBetween('event_date', [$startDate, $endDate]);
        })->where('status', 'accepted')->count();

        $attendanceCount = EventRegistration::whereHas('event', function($query) use ($ukmId, $startDate, $endDate) {
            $query->where('ukm_id', $ukmId)
                  ->whereBetween('event_date', [$startDate, $endDate]);
        })->whereNotNull('checked_in_at')->count();

        // Rates
        $acceptanceRate = $totalRegistrations > 0 ? round(($acceptedRegistrations / $totalRegistrations) * 100, 1) : 0;
        $attendanceRate = $acceptedRegistrations > 0 ? round(($attendanceCount / $acceptedRegistrations) * 100, 1) : 0;
        $avgRegistrationsPerEvent = $totalEvents > 0 ? round($totalRegistrations / $totalEvents, 1) : 0;

        // Top performing events
        $topEvents = Event::where('ukm_id', $ukmId)
            ->whereBetween('event_date', [$startDate, $endDate])
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
            ->take(10)
            ->get();

        // Monthly trends
        $monthlyData = Event::where('ukm_id', $ukmId)
            ->whereBetween('event_date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(event_date, "%Y-%m") as month, COUNT(*) as events_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyRegistrations = EventRegistration::whereHas('event', function($query) use ($ukmId, $startDate, $endDate) {
            $query->where('ukm_id', $ukmId)
                  ->whereBetween('event_date', [$startDate, $endDate]);
        })
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return view('ukms.reports.index', compact(
            'totalEvents',
            'upcomingEvents',
            'completedEvents',
            'totalRegistrations',
            'acceptedRegistrations',
            'attendanceCount',
            'acceptanceRate',
            'attendanceRate',
            'avgRegistrationsPerEvent',
            'topEvents',
            'monthlyData',
            'monthlyRegistrations',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show detailed event report
     */
    public function eventReport(Event $event)
    {
        $ukmId = Auth::user()->ukm_id;
        abort_if($event->ukm_id !== $ukmId, 403, 'Unauthorized access.');

        // Registration stats
        $totalRegistrations = $event->registrations()->count();
        $acceptedCount = $event->registrations()->where('status', 'accepted')->count();
        $rejectedCount = $event->registrations()->where('status', 'rejected')->count();
        $pendingCount = $event->registrations()->where('status', 'pending')->count();

        // Attendance stats
        $attendedCount = $event->registrations()
            ->where('status', 'accepted')
            ->whereNotNull('checked_in_at')
            ->count();
        $noShowCount = $acceptedCount - $attendedCount;

        // Rates
        $acceptanceRate = $totalRegistrations > 0 ? round(($acceptedCount / $totalRegistrations) * 100, 1) : 0;
        $attendanceRate = $acceptedCount > 0 ? round(($attendedCount / $acceptedCount) * 100, 1) : 0;
        $noShowRate = $acceptedCount > 0 ? round(($noShowCount / $acceptedCount) * 100, 1) : 0;

        // Registration timeline
        $registrationTimeline = $event->registrations()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Check-in timeline
        $checkInTimeline = $event->registrations()
            ->whereNotNull('checked_in_at')
            ->selectRaw('DATE(checked_in_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Status distribution over time
        $statusOverTime = $event->registrations()
            ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        return view('ukms.reports.event', compact(
            'event',
            'totalRegistrations',
            'acceptedCount',
            'rejectedCount',
            'pendingCount',
            'attendedCount',
            'noShowCount',
            'acceptanceRate',
            'attendanceRate',
            'noShowRate',
            'registrationTimeline',
            'checkInTimeline',
            'statusOverTime'
        ));
    }

    /**
     * Compare multiple events
     */
    public function compare(Request $request)
    {
        $ukmId = Auth::user()->ukm_id;

        $eventIds = $request->input('events', []);

        if (empty($eventIds)) {
            // Show selection page
            $events = Event::where('ukm_id', $ukmId)
                ->withCount('registrations')
                ->orderBy('event_date', 'desc')
                ->get();

            return view('ukms.reports.compare-select', compact('events'));
        }

        // Get events data
        $events = Event::where('ukm_id', $ukmId)
            ->whereIn('id', $eventIds)
            ->withCount([
                'registrations',
                'registrations as accepted_count' => function($query) {
                    $query->where('status', 'accepted');
                },
                'registrations as attended_count' => function($query) {
                    $query->whereNotNull('checked_in_at');
                }
            ])
            ->get();

        // Calculate metrics for each event
        $comparison = $events->map(function($event) {
            $acceptanceRate = $event->registrations_count > 0
                ? round(($event->accepted_count / $event->registrations_count) * 100, 1)
                : 0;

            $attendanceRate = $event->accepted_count > 0
                ? round(($event->attended_count / $event->accepted_count) * 100, 1)
                : 0;

            return [
                'id' => $event->id,
                'title' => $event->title,
                'date' => $event->event_date,
                'registrations' => $event->registrations_count,
                'accepted' => $event->accepted_count,
                'attended' => $event->attended_count,
                'acceptance_rate' => $acceptanceRate,
                'attendance_rate' => $attendanceRate,
                'no_show' => $event->accepted_count - $event->attended_count,
            ];
        });

        return view('ukms.reports.compare', compact('comparison', 'eventIds'));
    }
}
