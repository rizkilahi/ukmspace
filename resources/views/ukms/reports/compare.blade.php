@extends('layouts.app')

@section('title', 'Event Comparison - UKMSpace')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <a href="{{ route('ukm.reports.compare') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Select Different Events
                </a>
                <h2 class="fw-bold"><i class="bi bi-bar-chart-line"></i> Event Performance Comparison</h2>
                <p class="text-muted">Comparing {{ count($comparison) }} events</p>
            </div>
            <div class="col-md-4 text-end">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print Report
                </button>
            </div>
        </div>

        <!-- Comparison Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Performance Metrics</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Event</th>
                                <th class="text-center">Total Registrations</th>
                                <th class="text-center">Accepted</th>
                                <th class="text-center">Attended</th>
                                <th class="text-center">No-Show</th>
                                <th class="text-center">Acceptance Rate</th>
                                <th class="text-center">Attendance Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comparison as $event)
                                <tr>
                                    <td>
                                        <strong>{{ $event['title'] }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $event['date']->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $event['registrations'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $event['accepted'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $event['attended'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">{{ $event['no_show'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $event['acceptance_rate'] >= 80 ? 'bg-success' : ($event['acceptance_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $event['acceptance_rate'] }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $event['attendance_rate'] >= 80 ? 'bg-success' : ($event['attendance_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $event['attendance_rate'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Average Row -->
                            <tr class="table-secondary fw-bold">
                                <td>AVERAGE</td>
                                <td class="text-center">{{ round($comparison->avg('registrations'), 1) }}</td>
                                <td class="text-center">{{ round($comparison->avg('accepted'), 1) }}</td>
                                <td class="text-center">{{ round($comparison->avg('attended'), 1) }}</td>
                                <td class="text-center">{{ round($comparison->avg('no_show'), 1) }}</td>
                                <td class="text-center">{{ round($comparison->avg('acceptance_rate'), 1) }}%</td>
                                <td class="text-center">{{ round($comparison->avg('attendance_rate'), 1) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row g-3 mb-4">
            <!-- Registrations Comparison -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Registrations Comparison</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="registrationsChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Rates Comparison -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Acceptance vs Attendance Rates</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ratesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insights -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Comparison Insights</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="fw-semibold text-success">Best Performers:</h6>
                        <ul class="list-unstyled">
                            @php
                                $bestRegistrations = $comparison->sortByDesc('registrations')->first();
                                $bestAcceptance = $comparison->sortByDesc('acceptance_rate')->first();
                                $bestAttendance = $comparison->sortByDesc('attendance_rate')->first();
                            @endphp
                            <li class="mb-2">
                                <i class="bi bi-trophy-fill text-warning"></i>
                                <strong>Most Registrations:</strong><br>
                                {{ $bestRegistrations['title'] }} ({{ $bestRegistrations['registrations'] }})
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <strong>Best Acceptance:</strong><br>
                                {{ $bestAcceptance['title'] }} ({{ $bestAcceptance['acceptance_rate'] }}%)
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-person-check-fill text-info"></i>
                                <strong>Best Attendance:</strong><br>
                                {{ $bestAttendance['title'] }} ({{ $bestAttendance['attendance_rate'] }}%)
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-semibold text-danger">Needs Improvement:</h6>
                        <ul class="list-unstyled">
                            @php
                                $worstRegistrations = $comparison->sortBy('registrations')->first();
                                $worstAcceptance = $comparison->sortBy('acceptance_rate')->first();
                                $worstAttendance = $comparison->sortBy('attendance_rate')->first();
                            @endphp
                            <li class="mb-2">
                                <i class="bi bi-arrow-down-circle-fill text-danger"></i>
                                <strong>Lowest Registrations:</strong><br>
                                {{ $worstRegistrations['title'] }} ({{ $worstRegistrations['registrations'] }})
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-x-circle-fill text-danger"></i>
                                <strong>Lowest Acceptance:</strong><br>
                                {{ $worstAcceptance['title'] }} ({{ $worstAcceptance['acceptance_rate'] }}%)
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-person-x-fill text-danger"></i>
                                <strong>Lowest Attendance:</strong><br>
                                {{ $worstAttendance['title'] }} ({{ $worstAttendance['attendance_rate'] }}%)
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-semibold text-primary">Key Takeaways:</h6>
                        <ul class="list-unstyled">
                            @php
                                $avgAcceptance = round($comparison->avg('acceptance_rate'), 1);
                                $avgAttendance = round($comparison->avg('attendance_rate'), 1);
                                $totalReg = $comparison->sum('registrations');
                                $regVariance =
                                    $bestRegistrations['registrations'] - $worstRegistrations['registrations'];
                            @endphp
                            <li class="mb-2">
                                <i class="bi bi-graph-up"></i>
                                Average acceptance rate: <strong>{{ $avgAcceptance }}%</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-people"></i>
                                Average attendance rate: <strong>{{ $avgAttendance }}%</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-clipboard-data"></i>
                                Total registrations: <strong>{{ $totalReg }}</strong>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-arrows-expand"></i>
                                Registration variance: <strong>{{ $regVariance }}</strong>
                            </li>
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
        // Registrations Comparison Chart
        const regCtx = document.getElementById('registrationsChart').getContext('2d');
        new Chart(regCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($comparison->pluck('title')) !!},
                datasets: [{
                        label: 'Total Registrations',
                        data: {!! json_encode($comparison->pluck('registrations')) !!},
                        backgroundColor: 'rgba(13, 110, 253, 0.5)',
                        borderColor: 'rgb(13, 110, 253)',
                        borderWidth: 2
                    },
                    {
                        label: 'Accepted',
                        data: {!! json_encode($comparison->pluck('accepted')) !!},
                        backgroundColor: 'rgba(25, 135, 84, 0.5)',
                        borderColor: 'rgb(25, 135, 84)',
                        borderWidth: 2
                    },
                    {
                        label: 'Attended',
                        data: {!! json_encode($comparison->pluck('attended')) !!},
                        backgroundColor: 'rgba(13, 202, 240, 0.5)',
                        borderColor: 'rgb(13, 202, 240)',
                        borderWidth: 2
                    }
                ]
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

        // Rates Comparison Chart
        const ratesCtx = document.getElementById('ratesChart').getContext('2d');
        new Chart(ratesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($comparison->pluck('title')) !!},
                datasets: [{
                        label: 'Acceptance Rate (%)',
                        data: {!! json_encode($comparison->pluck('acceptance_rate')) !!},
                        borderColor: 'rgb(25, 135, 84)',
                        backgroundColor: 'rgba(25, 135, 84, 0.2)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Attendance Rate (%)',
                        data: {!! json_encode($comparison->pluck('attendance_rate')) !!},
                        borderColor: 'rgb(13, 202, 240)',
                        backgroundColor: 'rgba(13, 202, 240, 0.2)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
