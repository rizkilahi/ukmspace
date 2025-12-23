@extends('layouts.guest')

@section('title', 'Search Results - UKMSpace')

@section('content')
    <!-- Flash Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Header -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="fw-semibold mb-3">
                        @if ($query)
                            Search Results for "{{ $query }}"
                        @elseif($ukmId)
                            Events by {{ $ukms->firstWhere('id', $ukmId)->name ?? 'UKM' }}
                        @else
                            All Events
                        @endif
                    </h2>
                    <p class="text-muted">Found {{ $events->total() }} event(s)</p>
                </div>
            </div>

            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="ukm" class="form-select">
                            <option value="">All UKMs</option>
                            @foreach ($ukms as $u)
                                <option value="{{ $u->id }}" {{ $ukmId == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="query" class="form-control" placeholder="Search events..."
                            value="{{ $query }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Events Grid -->
    <section class="py-5">
        <div class="container">
            @if ($events->isEmpty())
                <div class="text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-search text-muted mb-3" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg>
                    <h4 class="text-muted">No Events Found</h4>
                    <p class="text-muted">Try adjusting your search criteria</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Back to Home</a>
                </div>
            @else
                <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 g-4">
                    @foreach ($events as $event)
                        <div class="col">
                            <div class="card card-event h-100">
                                @if ($event->image_url)
                                    <img src="{{ asset('storage/' . $event->image_url) }}" class="card-img-top"
                                        alt="{{ $event->title }}">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $event->title }}</h5>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-building"></i> {{ $event->ukm->name }}
                                    </p>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-calendar3"></i>
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                    </p>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-geo-alt"></i> {{ Str::limit($event->location, 30) }}
                                    </p>
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($events->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $events->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
