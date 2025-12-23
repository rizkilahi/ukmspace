@extends('layouts.app')

@section('title', 'Event Registrations - UKMSpace')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-semibold mb-1">Event Registrations</h2>
                        <p class="text-muted mb-0">{{ $event->title }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('ukm.events.qr-code', $event) }}" class="btn btn-primary">
                            <i class="bi bi-qr-code"></i> QR Code
                        </a>
                        <a href="{{ route('ukm.events.export', $event) }}" class="btn btn-success">
                            <i class="bi bi-download"></i> Export CSV
                        </a>
                        <a href="{{ route('ukm.events.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Events
                        </a>
                    </div>
                    <h3 class="fw-bold text-primary">{{ $registrations->total() }}</h3>
                    <p class="text-muted mb-0">Total Registrations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="fw-bold text-success">
                        {{ $event->registrations()->where('status', 'accepted')->count() }}</h3>
                    <p class="text-muted mb-0">Accepted</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="fw-bold text-warning">
                        {{ $event->registrations()->where('status', 'pending')->count() }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h3 class="fw-bold text-danger">
                        {{ $event->registrations()->where('status', 'rejected')->count() }}</h3>
                    <p class="text-muted mb-0">Rejected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    @if ($registrations->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                    class="bi bi-people text-muted mb-3" viewBox="0 0 16 16">
                    <path
                        d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                </svg>
                <h4 class="text-muted">No Registrations Yet</h4>
                <p class="text-muted">This event hasn't received any registrations.</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $index => $registration)
                                @php
                                    $statusClass = match ($registration->status) {
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        'pending' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $registrations->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                style="width: 32px; height: 32px;">
                                                {{ strtoupper(substr($registration->user->name, 0, 1)) }}
                                            </div>
                                            <strong>{{ $registration->user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $registration->user->email }}</td>
                                    <td>{{ $registration->user->phone ?? '-' }}</td>
                                    <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if ($registration->status !== 'accepted')
                                                <form action="{{ route('ukm.registrations.updateStatus', $registration) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button type="submit" class="btn btn-sm btn-success"
                                                        onclick="return confirm('Accept this registration?')">
                                                        <i class="bi bi-check-circle"></i> Accept
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($registration->status !== 'rejected')
                                                <form action="{{ route('ukm.registrations.updateStatus', $registration) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Reject this registration?')">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($registration->status !== 'pending')
                                                <form action="{{ route('ukm.registrations.updateStatus', $registration) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="btn btn-sm btn-warning"
                                                        onclick="return confirm('Set to pending?')">
                                                        <i class="bi bi-clock"></i> Pending
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if ($registrations->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $registrations->links() }}
            </div>
        @endif
    @endif
    </div>
    </div>
    </div>
@endsection
