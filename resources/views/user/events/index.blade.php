@extends('layouts.app')

@section('title', 'All Events')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">All Events</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('calendar') }}" class="btn btn-outline-primary">
                    <i class="bi bi-calendar3"></i> Calendar View
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter & Search -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('events') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search events..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">UKM</label>
                            <select name="ukm_id" class="form-select">
                                <option value="">All UKMs</option>
                                @foreach ($ukms as $ukm)
                                    <option value="{{ $ukm->id }}"
                                        {{ request('ukm_id') == $ukm->id ? 'selected' : '' }}>
                                        {{ $ukm->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date
                                    (Ascending)</option>
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date
                                    (Descending)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel"></i> Apply Filters
                            </button>
                            <a href="{{ route('events') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                            <span class="text-muted ms-3">
                                <i class="bi bi-list-check"></i> {{ $events->total() }} event(s) found
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($events as $event)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $event->image_url) }}" class="card-img-top" alt="Event Image">
                            <span class="position-absolute top-0 end-0 m-2 badge {{ $event->statusBadgeClass }}">
                                {{ ucfirst($event->status) }}
                            </span>
                            @if ($event->status === 'upcoming' && $event->daysUntil <= 7)
                                <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark">
                                    <i class="bi bi-clock"></i> {{ $event->daysUntil }} day(s) left
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                            </p>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt"></i> {{ $event->location }}
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false" title="Share">
                                        <i class="bi bi-share"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item"
                                                href="https://wa.me/?text={{ urlencode($event->title . ' - ' . route('events.show', $event->id)) }}"
                                                target="_blank">
                                                <i class="bi bi-whatsapp text-success"></i> WhatsApp
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event->id)) }}"
                                                target="_blank">
                                                <i class="bi bi-facebook text-primary"></i> Facebook
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(route('events.show', $event->id)) }}"
                                                target="_blank">
                                                <i class="bi bi-twitter text-info"></i> Twitter
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item copy-link" href="#"
                                                data-url="{{ route('events.show', $event->id) }}"
                                                onclick="event.preventDefault();">
                                                <i class="bi bi-link-45deg"></i> Copy Link
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @auth
                                @php
                                    $isRegistered = $event->registrations->where('user_id', auth()->id())->first();
                                @endphp

                                @if ($isRegistered)
                                    <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal"
                                        data-bs-target="#statusModal{{ $event->id }}">
                                        View Status
                                    </button>

                                    <!-- Status Modal -->
                                    <div class="modal fade" id="statusModal{{ $event->id }}" tabindex="-1"
                                        aria-labelledby="statusModalLabel{{ $event->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $event->id }}">Event
                                                        Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Event:</strong> {{ $event->title }}</p>
                                                    <p><strong>Date:</strong> {{ $event->event_date }}</p>
                                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                                    <p><strong>Status:</strong> {{ $isRegistered->status }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Status Modal -->
                                @else
                                    <!-- Trigger Register Modal -->
                                    <button type="button" class="btn btn-success mt-2" data-bs-toggle="modal"
                                        data-bs-target="#registerModal{{ $event->id }}">
                                        Register
                                    </button>

                                    <!-- Register Modal -->
                                    <div class="modal fade" id="registerModal{{ $event->id }}" tabindex="-1"
                                        aria-labelledby="registerModalLabel{{ $event->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="registerModalLabel{{ $event->id }}">
                                                        Confirm Registration</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Event:</strong> {{ $event->title }}</p>
                                                    <p><strong>Date:</strong> {{ $event->event_date }}</p>
                                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                                    <hr>
                                                    <p><strong>User:</strong> {{ auth()->user()->name }}</p>
                                                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                                    <p><strong>Phone:</strong> {{ auth()->user()->phone }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('events.register', $event) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">Confirm
                                                            Registration</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Register Modal -->
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary mt-2">Log In to Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            // Copy Link Functionality for event cards
            document.querySelectorAll('.copy-link').forEach(link => {
                link.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const linkText = this;
                    const originalHtml = linkText.innerHTML;

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(url).then(() => {
                            linkText.innerHTML = '<i class="bi bi-check-lg text-success"></i> Copied!';
                            setTimeout(() => {
                                linkText.innerHTML = originalHtml;
                            }, 2000);
                        }).catch(err => {
                            console.error('Failed to copy:', err);
                        });
                    } else {
                        // Fallback
                        const textArea = document.createElement('textarea');
                        textArea.value = url;
                        textArea.style.position = 'fixed';
                        textArea.style.left = '-999999px';
                        document.body.appendChild(textArea);
                        textArea.select();

                        try {
                            document.execCommand('copy');
                            linkText.innerHTML = '<i class="bi bi-check-lg text-success"></i> Copied!';
                            setTimeout(() => {
                                linkText.innerHTML = originalHtml;
                            }, 2000);
                        } catch (err) {
                            console.error('Fallback failed:', err);
                        }

                        document.body.removeChild(textArea);
                    }
                });
            });
        </script>
    @endpush
@endsection
