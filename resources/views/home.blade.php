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
            <h1 class="display-4 fw-medium mb-5">Your Wedding, Your Way</h1>
            <form class="search-form">
                <div class="row g-3">
                    <div class="col-md-4">
                        <select name="category" class="form-select search-input">
                            <option value="">Select UKM</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="event" class="form-select search-input">
                            <option value="">Select Event</option>
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
                <a href="#" class="text-dark">View All (10)</a>
            </div>
            <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 g-4">
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/220" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Destination Weddings</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/220" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Honeymoon & Travel Wedding</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/220" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Videographers Wedding</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/220" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Celebrant</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular UKM Section -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-medium">Popular UKM</h2>
                <a href="#" class="text-dark">View All (1000)</a>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/336" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">Lorem Isum Resort<br><span class="fw-semibold">Maldives</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/336" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">Lorem Isum Resort<br><span class="fw-semibold">India</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/336" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">Lorem Isum Resort<br><span class="fw-semibold">Abu Dhabi</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="/api/placeholder/352/336" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">Lorem Isum Resort<br><span class="fw-semibold">Dubai</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
