@extends('layouts.app')

@section('title', 'My Events - UKMSpace')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-semibold">My Event Registrations</h2>
                    <a href="{{ route('events') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Browse Events
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($registrations->isEmpty())
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                                class="bi bi-calendar-x text-muted mb-3" viewBox="0 0 16 16">
                                <path
                                    d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708z" />
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                            </svg>
                            <h4 class="text-muted">No Event Registrations Yet</h4>
                            <p class="text-muted">Start exploring events and register for activities you're interested in!
                            </p>
                            <a href="{{ route('events') }}" class="btn btn-primary mt-3">Browse Events</a>
                        </div>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($registrations as $registration)
                            @php
                                $event = $registration->event;
                                $statusClass = match ($registration->status) {
                                    'accepted' => 'success',
                                    'rejected' => 'danger',
                                    'pending' => 'warning',
                                    default => 'secondary',
                                };
                                $statusIcon = match ($registration->status) {
                                    'accepted' => 'check-circle-fill',
                                    'rejected' => 'x-circle-fill',
                                    'pending' => 'clock-fill',
                                    default => 'question-circle-fill',
                                };
                            @endphp

                            <div class="col-12">
                                <div class="card shadow-sm h-100">
                                    <div class="row g-0">
                                        <div class="col-md-3">
                                            @if ($event->image_url)
                                                <img src="{{ asset('storage/' . $event->image_url) }}"
                                                    class="img-fluid rounded-start h-100 object-fit-cover"
                                                    alt="{{ $event->title }}">
                                            @else
                                                <div
                                                    class="bg-secondary text-white d-flex align-items-center justify-content-center h-100 rounded-start">
                                                    <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="card-title mb-0">{{ $event->title }}</h5>
                                                    <span
                                                        class="badge bg-{{ $statusClass }} d-flex align-items-center gap-1">
                                                        <i class="bi bi-{{ $statusIcon }}"></i>
                                                        {{ ucfirst($registration->status) }}
                                                    </span>
                                                </div>

                                                <div class="mb-2">
                                                    <small class="text-muted d-flex align-items-center gap-1">
                                                        <i class="bi bi-building"></i>
                                                        {{ $event->ukm->name }}
                                                    </small>
                                                </div>

                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($event->description, 150) }}
                                                </p>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center gap-2 text-muted small">
                                                            <i class="bi bi-calendar3"></i>
                                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center gap-2 text-muted small">
                                                            <i class="bi bi-geo-alt"></i>
                                                            <span>{{ $event->location }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center gap-2 text-muted small">
                                                            <i class="bi bi-clock"></i>
                                                            <span>Registered:
                                                                {{ $registration->created_at->format('d M Y') }}</span>
                                                        </div>
                                                    </div>
                                                    @if ($registration->status === 'accepted')
                                                        <div class="col-md-6">\n <div
                                                                class="d-flex align-items-center gap-2 text-success small fw-semibold">
                                                                <i class="bi bi-check-circle"></i>
                                                                <span>You're attending!</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('events.show', $event) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i> View Details
                                                    </a>

                                                    @if ($registration->status === 'pending')
                                                        <span class="badge bg-warning text-dark d-flex align-items-center">
                                                            <i class="bi bi-hourglass-split me-1"></i> Awaiting Approval
                                                        </span>
                                                    @elseif($registration->status === 'rejected')
                                                        <span class="badge bg-danger d-flex align-items-center">
                                                            <i class="bi bi-x-circle me-1"></i> Registration Declined
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
