@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <a href="{{ route('ukm.events.manage', $event) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Event
                </a>
            </div>
        </div>

        <div class="row">
            <!-- QR Code Section -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-qr-code"></i> QR Code Check-In</h5>
                    </div>
                    <div class="card-body text-center">
                        <h6 class="mb-3">{{ $event->title }}</h6>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-calendar"></i> {{ $event->event_date->format('M d, Y') }}<br>
                            <i class="bi bi-clock"></i> {{ $event->event_date->format('g:i A') }}
                        </p>

                        <!-- QR Code using Google Charts API -->
                        <div class="qr-code-container mb-3">
                            <img src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl={{ urlencode($checkInUrl) }}&choe=UTF-8"
                                alt="QR Code for Check-In" class="img-fluid border rounded p-2" style="max-width: 300px;">
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle"></i> Scan this QR code to check in to the event
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="printQR()">
                                <i class="bi bi-printer"></i> Print QR Code
                            </button>
                            <button class="btn btn-outline-secondary" onclick="downloadQR()">
                                <i class="bi bi-download"></i> Download QR Code
                            </button>
                            <button class="btn btn-outline-info" onclick="copyLink()">
                                <i class="bi bi-link-45deg"></i> Copy Check-In Link
                            </button>
                        </div>

                        <input type="hidden" id="checkInUrl" value="{{ $checkInUrl }}">
                    </div>
                </div>

                <!-- Attendance Stats -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">Attendance Statistics</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="mb-0 text-primary">{{ $totalRegistrations }}</h4>
                                <small class="text-muted">Total</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-0 text-success">{{ $checkedInCount }}</h4>
                                <small class="text-muted">Checked In</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-0 text-warning">{{ $totalRegistrations - $checkedInCount }}</h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceRate }}%;"
                                aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $attendanceRate }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-people"></i> Attendance List</h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary"
                                    onclick="filterAttendance('all')">All</button>
                                <button class="btn btn-sm btn-outline-success"
                                    onclick="filterAttendance('checked-in')">Checked In</button>
                                <button class="btn btn-sm btn-outline-warning"
                                    onclick="filterAttendance('pending')">Pending</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($registrations->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">No accepted registrations yet</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th width="15%">Status</th>
                                            <th width="20%">Check-In Time</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registrations as $index => $registration)
                                            <tr class="attendance-row"
                                                data-status="{{ $registration->checked_in_at ? 'checked-in' : 'pending' }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $registration->user->name }}</strong>
                                                </td>
                                                <td>{{ $registration->user->email }}</td>
                                                <td>{{ $registration->user->phone ?? '-' }}</td>
                                                <td>
                                                    @if ($registration->checked_in_at)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Checked In
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-clock"></i> Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($registration->checked_in_at)
                                                        <small>
                                                            {{ $registration->checked_in_at->format('g:i A') }}<br>
                                                            <span
                                                                class="text-muted">{{ $registration->checked_in_at->format('M d, Y') }}</span>
                                                            @if ($registration->check_in_method)
                                                                <br><span
                                                                    class="badge bg-info">{{ ucfirst($registration->check_in_method) }}</span>
                                                            @endif
                                                        </small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form
                                                        action="{{ route('ukm.events.manual-checkin', [$event, $registration]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $registration->checked_in_at ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                            title="{{ $registration->checked_in_at ? 'Remove Check-In' : 'Manual Check-In' }}">
                                                            <i
                                                                class="bi {{ $registration->checked_in_at ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                                                        </button>
                                                    </form>
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
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .card-header,
            nav,
            footer,
            .attendance-row {
                display: none !important;
            }

            .qr-code-container {
                page-break-inside: avoid;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>

    <script>
        function printQR() {
            window.print();
        }

        function downloadQR() {
            const qrImage = document.querySelector('.qr-code-container img');
            const link = document.createElement('a');
            link.href = qrImage.src;
            link.download = 'event-qr-code-{{ $event->id }}.png';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function copyLink() {
            const url = document.getElementById('checkInUrl').value;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(() => {
                    showAlert('Check-in link copied to clipboard!', 'success');
                }).catch(() => {
                    fallbackCopy(url);
                });
            } else {
                fallbackCopy(url);
            }
        }

        function fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');
                showAlert('Check-in link copied to clipboard!', 'success');
            } catch (err) {
                showAlert('Failed to copy link', 'danger');
            }

            document.body.removeChild(textArea);
        }

        function filterAttendance(status) {
            const rows = document.querySelectorAll('.attendance-row');

            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.dataset.status === status ? '' : 'none';
                }
            });
        }

        function showAlert(message, type) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className =
                `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            document.body.appendChild(alertDiv);

            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Auto-refresh every 30 seconds to update attendance
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
@endsection
