@extends('layouts.app')

@section('title', $event->title . ' - UKMSpace')

@push('meta')
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="event">
    <meta property="og:url" content="{{ route('events.show', $event->id) }}">
    <meta property="og:title" content="{{ $event->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($event->description), 200) }}">
    <meta property="og:image" content="{{ asset('storage/' . $event->image_url) }}">
    <meta property="og:site_name" content="UKMSpace">
    <meta property="event:start_time" content="{{ $event->event_date->toIso8601String() }}">
    <meta property="event:location" content="{{ $event->location }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('events.show', $event->id) }}">
    <meta name="twitter:title" content="{{ $event->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($event->description), 200) }}">
    <meta name="twitter:image" content="{{ asset('storage/' . $event->image_url) }}">

    <!-- Additional Meta Tags -->
    <meta name="description" content="{{ Str::limit(strip_tags($event->description), 160) }}">
    <meta name="keywords" content="event, {{ $event->ukm->name }}, {{ $event->title }}, campus event">
    <link rel="canonical" href="{{ route('events.show', $event->id) }}">
@endpush

@section('content')
    <div class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events') }}">Events</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($event->title, 30) }}</li>
            </ol>
        </nav>

        <!-- Event Status Badge -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">{{ $event->title }}</h1>
            <span class="badge {{ $event->statusBadgeClass }} fs-6">
                {{ ucfirst($event->status) }}
            </span>
        </div>

        <!-- Countdown Timer for Upcoming Events -->
        @if ($event->status === 'upcoming')
            <div class="alert alert-info d-flex align-items-center mb-4">
                <i class="bi bi-clock-history me-2 fs-4"></i>
                <div class="flex-grow-1">
                    @if ($event->daysUntil === 0)
                        <strong>Event is Tomorrow!</strong>
                    @elseif($event->daysUntil === 1)
                        <strong>Event is in 1 day</strong>
                    @else
                        <strong>Event is in {{ $event->daysUntil }} days</strong>
                    @endif
                    <br>
                    <small>{{ \Carbon\Carbon::parse($event->event_date)->diffForHumans() }}</small>
                </div>
            </div>
        @elseif($event->status === 'ongoing')
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="bi bi-broadcast me-2 fs-4"></i>
                <div>
                    <strong>Event is Happening Today!</strong>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="position-relative">
                    <img src="{{ asset('storage/' . $event->image_url) }}" class="img-fluid rounded shadow"
                        alt="Event Image">
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Event Details</h3>

                        <div class="mb-3">
                            <h5><i class="bi bi-file-text text-primary"></i> Description</h5>
                            <p class="text-muted">{{ $event->description }}</p>
                        </div>

                        <div class="mb-3">
                            <h5><i class="bi bi-calendar3 text-primary"></i> Date & Time</h5>
                            <p class="text-muted mb-1">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <h5><i class="bi bi-geo-alt text-primary"></i> Location</h5>
                            <p class="text-muted">{{ $event->location }}</p>
                        </div>

                        <div class="mb-4">
                            <h5><i class="bi bi-building text-primary"></i> Organized by</h5>
                            <div class="d-flex align-items-center">
                                @if ($event->ukm->logo)
                                    <img src="{{ asset('storage/' . $event->ukm->logo) }}" alt="{{ $event->ukm->name }}"
                                        class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                                <span class="fw-semibold">{{ $event->ukm->name }}</span>
                            </div>
                        </div>

                        <!-- Social Sharing Section -->
                        <div class="mb-4">
                            <h5><i class="bi bi-share text-primary"></i> Share This Event</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . route('events.show', $event->id)) }}"
                                    target="_blank" class="btn btn-success btn-sm" title="Share on WhatsApp">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>

                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event->id)) }}"
                                    target="_blank" class="btn btn-primary btn-sm" title="Share on Facebook">
                                    <i class="bi bi-facebook"></i> Facebook
                                </a>

                                <!-- Twitter -->
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(route('events.show', $event->id)) }}"
                                    target="_blank" class="btn btn-info btn-sm text-white" title="Share on Twitter">
                                    <i class="bi bi-twitter"></i> Twitter
                                </a>

                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('events.show', $event->id)) }}"
                                    target="_blank" class="btn btn-primary btn-sm"
                                    style="background-color: #0077b5; border-color: #0077b5;" title="Share on LinkedIn">
                                    <i class="bi bi-linkedin"></i> LinkedIn
                                </a>

                                <!-- Email -->
                                <a href="mailto:?subject={{ urlencode($event->title) }}&body={{ urlencode('Check out this event: ' . $event->title . ' - ' . route('events.show', $event->id)) }}"
                                    class="btn btn-secondary btn-sm" title="Share via Email">
                                    <i class="bi bi-envelope"></i> Email
                                </a>

                                <!-- Copy Link -->
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="copyLinkBtn"
                                    data-url="{{ route('events.show', $event->id) }}" title="Copy Link">
                                    <i class="bi bi-link-45deg"></i> Copy Link
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Share this event with your friends and colleagues
                            </small>
                        </div>

                        <hr>

                        @auth
                            @php
                                $registration = $event->registrations->where('user_id', auth()->id())->first();
                                $role = auth()->user()->role;
                            @endphp

                            @if ($role === 'ukm')
                                <p class="text-muted"><i class="bi bi-info-circle"></i> UKMs cannot register for events</p>
                            @elseif ($registration)
                                <div
                                    class="alert alert-{{ match ($registration->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    } }} mb-0">
                                    <div class="d-flex align-items-center">
                                        <i
                                            class="bi bi-{{ match ($registration->status) {
                                                'approved' => 'check-circle-fill',
                                                'rejected' => 'x-circle-fill',
                                                default => 'clock-fill',
                                            } }} me-2 fs-4"></i>
                                        <div>
                                            <strong>Registration {{ ucfirst($registration->status) }}</strong><br>
                                            <small>Registered on {{ $registration->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($event->status === 'completed')
                                    <button type="button" class="btn btn-secondary w-100" disabled>
                                        <i class="bi bi-calendar-x"></i> Event Completed
                                    </button>
                                @else
                                    <form action="{{ route('events.register', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 btn-lg">
                                            <i class="bi bi-calendar-check"></i> Register for Event
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Log In to Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Copy Link Functionality
            document.getElementById('copyLinkBtn')?.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const btn = this;
                const originalHtml = btn.innerHTML;

                // Modern clipboard API
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(() => {
                        // Success feedback
                        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
                        btn.classList.remove('btn-outline-secondary');
                        btn.classList.add('btn-success');

                        // Reset after 2 seconds
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                        alert('Failed to copy link. Please try again.');
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.select();

                    try {
                        document.execCommand('copy');
                        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
                        btn.classList.remove('btn-outline-secondary');
                        btn.classList.add('btn-success');

                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    } catch (err) {
                        console.error('Fallback: Failed to copy', err);
                        alert('Failed to copy link. Please try again.');
                    }

                    document.body.removeChild(textArea);
                }
            });

            // Track social share clicks (optional analytics)
            document.querySelectorAll('a[href*="facebook"], a[href*="twitter"], a[href*="linkedin"], a[href*="wa.me"]').forEach(
                link => {
                    link.addEventListener('click', function() {
                        const platform = this.textContent.trim();
                        console.log(`Shared on ${platform}`);
                        // You can add analytics tracking here if needed
                    });
                });
        </script>
    @endpush
@endsection
