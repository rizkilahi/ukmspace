<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">UKMSpace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/events">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="/media">Media</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contacts">Contacts</a></li>
                </ul>
                <div class="ms-3">
                    @auth
                        <a href="/dashboard" class="btn btn-primary btn-sm">Dashboard</a>
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="btn btn-primary btn-sm">Login</a>
                        <a href="/register" class="btn btn-outline-primary btn-sm">Signup</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center bg-light py-5">
        <div class="container">
            <h1 class="display-4 mb-4">Satu Website, Solusi Event Anda</h1>
            <form action="#" method="GET" class="row g-3 justify-content-center">
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="location" class="form-select">
                        <option value="">Select Location</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </section>

    {{-- <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Browse by Category</h2>
            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{ $category->image }}" class="card-img-top" alt="{{ $category->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}

    {{-- <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">What People Say</h2>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($testimonials as $index => $testimonial)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <blockquote class="blockquote text-center">
                                <p class="mb-4">{{ $testimonial->content }}</p>
                                <footer class="blockquote-footer">{{ $testimonial->author }}</footer>
                            </blockquote>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section> --}}

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p>© {{ date('Y') }} UKMSpace</p>
            <a href="#" class="text-white text-decoration-none">Privacy Policy</a> |
            <a href="#" class="text-white text-decoration-none">Terms of Service</a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
