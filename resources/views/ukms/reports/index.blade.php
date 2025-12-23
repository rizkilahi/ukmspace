@extends('layouts.app')

@section('title', 'Advanced Reports - UKMSpace')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold"><i class="bi bi-file-earmark-bar-graph"></i> Advanced Reports</h2>
                <p class="text-muted">Comprehensive analytics and insights for your events</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('ukm.reports.compare') }}" class="btn btn-primary">
                    <i class="bi bi-bar-chart-line"></i> Compare Events
                </a>
                <a href="{{ route('ukm.analytics') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Analytics
                </a>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('ukm.reports.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Apply Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Total Events</p>
                                <h3 class="fw-bold mb-0">{{ $totalEvents }}</h3>
                                <small class="text-success">
                                    <i class="bi bi-calendar-check"></i> {{ $completedEvents }} completed
                                </small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-calendar-event text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Total Registrations</p>
                                <h3 class="fw-bold mb-0">{{ $totalRegistrations }}</h3>
                                <small class="text-info">
                                    <i class="bi bi-graph-up"></i> {{ $avgRegistrationsPerEvent }} avg/event
                                </small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-people text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Acceptance Rate</p>
                                <h3 class="fw-bold mb-0">{{ $acceptanceRate }}%</h3>
                                <small class="text-success">
                                    <i class="bi bi-check-circle"></i> {{ $acceptedRegistrations }} accepted
                                </small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Attendance Rate</p>
                                <h3 class="fw-bold mb-0">{{ $attendanceRate }}%</h3>
                                <small class="text-warning">
                                    <i class="bi bi-person-check"></i> {{ $attendanceCount }} attended
                                </small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-person-check text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-3 mb-4">
            <!-- Monthly Events Trend -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Monthly Events Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyEventsChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Registrations Trend -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Monthly Registrations Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyRegistrationsChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Events -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top Performing Events</h5>
            </div>
            <div class="card-body p-0">
                @if ($topEvents->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-3">No events in selected date range</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Event Name</th>
                                    <th width="10%">Date</th>
                                    <th width="10%" class="text-center">Registrations</th>
                                    <th width="10%" class="text-center">Accepted</th>
                                    <th width="10%" class="text-center">Attended</th>
                                    <th width="10%" class="text-center">Attendance Rate</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topEvents as $index => $event)
                                    @php
                                        $attendanceRate =
                                            $event->accepted_count > 0
                                                ? round(($event->attended_count / $event->accepted_count) * 100, 1)
                                                : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $event->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $event->location }}</small>
                                        </td>
                                        <td>{{ $event->event_date->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $event->registrations_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $event->accepted_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $event->attended_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $attendanceRate >= 80 ? 'bg-success' : ($attendanceRate >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $attendanceRate }}%
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('ukm.reports.event', $event) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-text"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Monthly Events Chart
        const monthlyEventsCtx = document.getElementById('monthlyEventsChart').getContext('2d');
        new Chart(monthlyEventsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyData->pluck('month')) !!},
                datasets: [{
                    label: 'Events',
                    data: {!! json_encode($monthlyData->pluck('events_count')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Monthly Registrations Chart
        const monthlyRegCtx = document.getElementById('monthlyRegistrationsChart').getContext('2d');
        new Chart(monthlyRegCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyRegistrations->pluck('month')) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($monthlyRegistrations->pluck('count')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
