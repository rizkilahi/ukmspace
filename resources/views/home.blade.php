@extends('layouts.guest')

@section('title', 'Home - UKMSpace')

@section('content')
    <!-- Flash Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Hero Section -->
    <section class="hero">
        <div class="container text-center">
            <h1 class="display-4 fw-medium mb-5">UKM Space</h1>
            <form class="search-form">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="category" class="form-select search-input">
                            @foreach ($ukms as $u)
                                <option value="">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="event" class="form-select search-input">
                            @foreach ($events as $e)
                                <option value="">{{ $e->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn search-button text-white fw-semibold w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Popular Event Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-medium">Popular Event</h2>
                <a href="{{ route('events') }}" class="text-dark">View All ({{ $popularEvents->count() }})</a>
            </div>
            <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 g-4">
                @foreach ($popularEvents as $event)
                    <div class="col">
                        <div class="card card-event h-100">
                            <img src="{{ asset('storage/' . $event->image_url) }}" class="card-img-top" alt="Event">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $event->title }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular UKM Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-medium">Popular UKM</h2>
                <a href="" class="text-dark">View All ({{ $popularUKMs->count() }})</a>
            </div>
            <div class="row g-4">
                @foreach ($popularUKMs as $ukm)
                    <div class="col-md-3">
                        <div class="card card-event h-100">
                            <img src="{{ asset('storage/' . $ukm->logo) }}" class="card-img-top" alt="UKM Logo">
                            <div class="card-body">
                                <p class="explore-link text-end">Explore</p>
                                <h5 class="card-title">{{ $ukm->name }}</h5>
                                <p class="text-muted">{{ Str::limit($ukm->description, 50) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
