@extends('layouts.app')

@section('title', 'Event Analytics - UKMSpace')

@section('content')
    <div class="container my-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-semibold mb-1">
                    <i class="bi bi-bar-chart-fill text-primary"></i> Analytics Dashboard
                </h2>
                <p class="text-muted mb-0">Track your event performance and registrations</p>
            </div>
            <a href="{{ route('ukm.events.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Events
            </a>
        </div>

        <!-- Key Metrics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Events -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <i class="bi bi-calendar-event fs-3 text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 small">Total Events</h6>
                                <h3 class="fw-bold mb-0">{{ $totalEvents }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 small">
                            <span class="text-success"><i class="bi bi-calendar-plus"></i> {{ $upcomingEvents }}
                                upcoming</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Registrations -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded p-3">
                                    <i class="bi bi-people-fill fs-3 text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 small">Total Registrations</h6>
                                <h3 class="fw-bold mb-0">{{ $totalRegistrations }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 small">
                            <span class="text-warning"><i class="bi bi-hourglass-split"></i> {{ $pendingCount }}
                                pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 rounded p-3">
                                    <i class="bi bi-check-circle-fill fs-3 text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 small">Accepted</h6>
                                <h3 class="fw-bold mb-0">{{ $acceptedCount }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 small">
                            <span class="text-info"><i class="bi bi-graph-up"></i> {{ $approvalRate }}% rate</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-danger bg-opacity-10 rounded p-3">
                                    <i class="bi bi-x-circle-fill fs-3 text-danger"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="text-muted mb-1 small">Rejected</h6>
                                <h3 class="fw-bold mb-0">{{ $rejectedCount }}</h3>
                            </div>
                        </div>
                        <div class="mt-3 small">
                            <span class="text-muted"><i class="bi bi-dash-circle"></i> {{ $completedEvents }}
                                completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Registration Trend Chart -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-graph-up text-primary"></i> Registration Trend
                        </h5>
                        <small class="text-muted">Last 6 months</small>
                    </div>
                    <div class="card-body">
                        <canvas id="registrationTrendChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-pie-chart text-primary"></i> Status Distribution
                        </h5>
                        <small class="text-muted">Current breakdown</small>
                    </div>
                    <div class="card-body">
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Events & Recent Registrations Row -->
        <div class="row g-4">
            <!-- Top Events -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-trophy text-warning"></i> Top 5 Events
                        </h5>
                        <small class="text-muted">By registrations</small>
                    </div>
                    <div class="card-body">
                        @if ($topEvents->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($topEvents as $index => $event)
                                    <div class="list-group-item px-0 d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="badge bg-primary rounded-circle"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                <strong>{{ $index + 1 }}</strong>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $event->title }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3"></i> {{ $event->event_date->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <div class="badge bg-success rounded-pill">
                                            {{ $event->registrations_count }} <i class="bi bi-people-fill"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mb-0 mt-2">No events yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Registrations -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-clock-history text-info"></i> Recent Registrations
                        </h5>
                        <small class="text-muted">Latest 10 entries</small>
                    </div>
                    <div class="card-body">
                        @if ($recentRegistrations->count() > 0)
                            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                @foreach ($recentRegistrations as $registration)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $registration->user->name }}</h6>
                                                <small
                                                    class="text-muted d-block">{{ $registration->event->title }}</small>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock"></i>
                                                    {{ $registration->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <span
                                                class="badge
                                            @if ($registration->status === 'accepted') bg-success
                                            @elseif($registration->status === 'rejected') bg-danger
                                            @else bg-warning @endif">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                                <p class="mb-0 mt-2">No registrations yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Status Breakdown -->
        <div class="row g-4 mt-0">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-list-check text-primary"></i> Event Status Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="bi bi-calendar-plus fs-1 text-primary"></i>
                                    <h4 class="mt-2 fw-bold">{{ $upcomingEvents }}</h4>
                                    <p class="text-muted mb-0">Upcoming Events</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="bi bi-calendar-check fs-1 text-success"></i>
                                    <h4 class="mt-2 fw-bold">{{ $ongoingEvents }}</h4>
                                    <p class="text-muted mb-0">Ongoing Events</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3">
                                    <i class="bi bi-calendar-x fs-1 text-secondary"></i>
                                    <h4 class="mt-2 fw-bold">{{ $completedEvents }}</h4>
                                    <p class="text-muted mb-0">Completed Events</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Registration Trend Chart
            const trendCtx = document.getElementById('registrationTrendChart');
            if (trendCtx) {
                const monthlyData = @json($monthlyData);

                // Generate last 6 months labels
                const months = [];
                const counts = [];

                for (let i = 5; i >= 0; i--) {
                    const date = new Date();
                    date.setMonth(date.getMonth() - i);
                    const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
                    months.push(date.toLocaleDateString('en-US', {
                        month: 'short',
                        year: 'numeric'
                    }));

                    const dataPoint = monthlyData.find(d => d.month === monthKey);
                    counts.push(dataPoint ? dataPoint.count : 0);
                }

                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Registrations',
                            data: counts,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Status Distribution Pie Chart
            const statusCtx = document.getElementById('statusDistributionChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Accepted', 'Pending', 'Rejected'],
                        datasets: [{
                            data: [{{ $acceptedCount }}, {{ $pendingCount }}, {{ $rejectedCount }}],
                            backgroundColor: [
                                '#0dcaf0',
                                '#ffc107',
                                '#dc3545'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
