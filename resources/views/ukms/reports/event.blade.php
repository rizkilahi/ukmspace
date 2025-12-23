@extends('layouts.app')

@section('title', 'Event Report - ' . $event->title)

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <a href="{{ route('ukm.reports.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Reports
                </a>
                <h2 class="fw-bold">{{ $event->title }}</h2>
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar"></i> {{ $event->event_date->format('F d, Y g:i A') }} |
                    <i class="bi bi-geo-alt"></i> {{ $event->location }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print Report
                </button>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="fw-bold text-primary mb-0">{{ $totalRegistrations }}</h2>
                        <p class="text-muted mb-0">Total Registrations</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="fw-bold text-success mb-0">{{ $acceptanceRate }}%</h2>
                        <p class="text-muted mb-0">Acceptance Rate</p>
                        <small class="text-success">{{ $acceptedCount }} accepted</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="fw-bold text-info mb-0">{{ $attendanceRate }}%</h2>
                        <p class="text-muted mb-0">Attendance Rate</p>
                        <small class="text-info">{{ $attendedCount }}/{{ $acceptedCount }} attended</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="fw-bold text-danger mb-0">{{ $noShowRate }}%</h2>
                        <p class="text-muted mb-0">No-Show Rate</p>
                        <small class="text-danger">{{ $noShowCount }} no-shows</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Registration Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Status Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-check-circle text-success"></i> Accepted</span>
                                <strong>{{ $acceptedCount }}</strong>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success"
                                    style="width: {{ $totalRegistrations > 0 ? ($acceptedCount / $totalRegistrations) * 100 : 0 }}%">
                                    {{ $totalRegistrations > 0 ? round(($acceptedCount / $totalRegistrations) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-clock text-warning"></i> Pending</span>
                                <strong>{{ $pendingCount }}</strong>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-warning"
                                    style="width: {{ $totalRegistrations > 0 ? ($pendingCount / $totalRegistrations) * 100 : 0 }}%">
                                    {{ $totalRegistrations > 0 ? round(($pendingCount / $totalRegistrations) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><i class="bi bi-x-circle text-danger"></i> Rejected</span>
                                <strong>{{ $rejectedCount }}</strong>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-danger"
                                    style="width: {{ $totalRegistrations > 0 ? ($rejectedCount / $totalRegistrations) * 100 : 0 }}%">
                                    {{ $totalRegistrations > 0 ? round(($rejectedCount / $totalRegistrations) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timelines -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Registration Timeline</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="registrationTimelineChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Check-In Timeline</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="checkInTimelineChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insights -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Key Insights</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-semibold">Positive Indicators:</h6>
                        <ul class="list-unstyled">
                            @if ($acceptanceRate >= 80)
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Excellent
                                    acceptance rate ({{ $acceptanceRate }}%)</li>
                            @endif
                            @if ($attendanceRate >= 80)
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> High attendance
                                    rate ({{ $attendanceRate }}%)</li>
                            @endif
                            @if ($totalRegistrations >= 50)
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Strong
                                    registration interest ({{ $totalRegistrations }} registrations)</li>
                            @endif
                            @if ($noShowRate <= 10)
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Low no-show rate
                                    ({{ $noShowRate }}%)</li>
                            @endif
                            @if ($acceptanceRate < 80 && $attendanceRate < 80 && $totalRegistrations < 50 && $noShowRate > 10)
                                <li class="text-muted"><em>No significant positive indicators</em></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold">Areas for Improvement:</h6>
                        <ul class="list-unstyled">
                            @if ($acceptanceRate < 60)
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Low
                                    acceptance rate - review selection criteria</li>
                            @endif
                            @if ($attendanceRate < 60)
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Low
                                    attendance - consider reminder notifications</li>
                            @endif
                            @if ($noShowRate > 20)
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i> High
                                    no-show rate - improve engagement</li>
                            @endif
                            @if ($totalRegistrations < 20)
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Low
                                    registration interest - enhance promotion</li>
                            @endif
                            @if ($acceptanceRate >= 60 && $attendanceRate >= 60 && $noShowRate <= 20 && $totalRegistrations >= 20)
                                <li class="text-muted"><em>No major concerns identified</em></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            nav,
            footer {
                display: none !important;
            }
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Accepted', 'Pending', 'Rejected'],
                datasets: [{
                    data: [{{ $acceptedCount }}, {{ $pendingCount }}, {{ $rejectedCount }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgb(40, 167, 69)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Registration Timeline Chart
        const regTimelineCtx = document.getElementById('registrationTimelineChart').getContext('2d');
        new Chart(regTimelineCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($registrationTimeline->pluck('date')) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($registrationTimeline->pluck('count')) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        // Check-In Timeline Chart
        const checkInTimelineCtx = document.getElementById('checkInTimelineChart').getContext('2d');
        new Chart(checkInTimelineCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($checkInTimeline->pluck('date')) !!},
                datasets: [{
                    label: 'Check-Ins',
                    data: {!! json_encode($checkInTimeline->pluck('count')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
