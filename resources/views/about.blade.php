@extends('layouts.guest')

@section('title', 'About - UKM Space')

@section('content')
<!-- Hero Section -->
<section class="hero bg-light text-center py-5">
    <div class="container">
        <h1 class="display-4 fw-bold">We Are UKM Space</h1>
        <p class="lead text-muted">Your Ultimate College Community Event Partner</p>
    </div>
</section>

<!-- About Section -->
<section class="about-section py-5">
    <div class="container">
        <h2 class="text-center mb-4">About Us</h2>
        <p class="text-muted text-justify">
            UKMSpace is a vibrant online platform dedicated to connecting students with campus events and organizations. We serve as a hub where campus organizations can promote their events, fostering innovation, collaboration, and community engagement.
        </p>
        <p class="text-muted text-justify mt-4">
        Whether you're looking for seminars, competitions, cultural festivals, or workshops, UKMSpace makes it easy to discover and participate in exciting opportunities. Our mission is to empower students to make the most of their college experience by bridging the gap between organizations and their audiences.
        </p>
        <p class="text-muted text-justify mt-4">
        Join us in building a thriving community where ideas flourish, talents are celebrated, and every student finds their place to shine. Let UKMSpace be your partner in making meaningful connections and unforgettable memories.
    </p>
    </div>
</section>

<!-- Stats Section -->
<section class="stats py-5 bg-white">
    <div class="container text-center">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stat-item">
                    <h3 class="display-5 fw-bold">10,000+</h3>
                    <p class="text-muted">Registered UKM</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h3 class="display-5 fw-bold">20,000+</h3>
                    <p class="text-muted">Events</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h3 class="display-5 fw-bold">1,000+</h3>
                    <p class="text-muted">Location</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <h3 class="display-5 fw-bold">1.5M</h3>
                    <p class="text-muted">Satisfied Customer</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
