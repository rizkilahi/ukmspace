@extends('layouts.guest')

@section('title', 'Home - UKMSpace')

@section('content')

<style>
    .card-img-top {
        object-fit: cover;
        width: 100%;
        height: 200px;
    }
</style>

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
            <h1 class="display-4 fw-medium mb-5">UKM Space: Your Gateway to Innovation and Collaboration</h1>
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
                        <img src="https://jakarta.telkomuniversity.ac.id/wp-content/uploads/2024/03/Event-Studium-Generale-Sisfo-410x260.webp" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Seminar Usability Testing Sistem Informasi</h5>
                            <p class="card-"></p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://i.ytimg.com/vi/Lbfr15cQgK8/maxresdefault.jpg" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Sidang Senat Telkom University</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://i.ytimg.com/vi/1irhGoWpt4A/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLA7jYiDUu88zhICX6WJnMM7DVYqzA" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Literacy Event</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://jakarta.telkomuniversity.ac.id/wp-content/uploads/2024/11/LAFEST-2024-wbep-410x260.webp" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Lafest</h5>
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
                        <img src="https://b856188.smushcdn.com/856188/wp-content/uploads/2022/03/catur-410x260.png?lossy=2&strip=0&webp=1" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">UKM Catur<br><span class="fw-semibold">Maldives</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="https://b856188.smushcdn.com/856188/wp-content/uploads/2022/03/capoeira-410x260.jpg?lossy=2&strip=0&webp=1" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">UKM Capoeira Brasil<br><span class="fw-semibold">India</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="https://b856188.smushcdn.com/856188/wp-content/uploads/2022/03/badminton-410x260.jpg?lossy=2&strip=0&webp=1" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">UKM Badminton<br><span class="fw-semibold">Abu Dhabi</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="https://b856188.smushcdn.com/856188/wp-content/uploads/2022/03/voli-410x260.webp?lossy=2&strip=0&webp=1" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <p class="explore-link text-end">Explore</p>
                            <h5 class="card-title">UKM Voli<br><span class="fw-semibold">Dubai</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
