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
                            @foreach($ukms as $u)
                            <option value="">{{$u["name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="event" class="form-select search-input">
                            @foreach($events as $e)
                            <option value="">{{$e["title"]}}</option>
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
                <a href="/ukm/events" class="text-dark">View All (30)</a>
            </div>
            <div class="row row-cols-lg-4 row-cols-md-3 row-cols-2 g-4">
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/people-festival_1160-736.jpg?t=st=1736428958~exp=1736432558~hmac=a49711c4caeaf9a142760ae751a0260f2fd1733b813e9c70c2df36fb0bbbe19d&w=1380" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Live Music Night</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/high-angle-man-holding-camera_23-2148532587.jpg?t=st=1736429003~exp=1736432603~hmac=3e987c0df0e4289517c96c47262577b8a8e6b3990c89814a3dc3381421584891&w=1800" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Photography Workshop</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/icon-painter-works-new-icon-with-brush_1398-3392.jpg?t=st=1736429044~exp=1736432644~hmac=8653c991dc02ad1069241b4a888d0a99b2dae6a231145b6fdcfe584abb894bce&w=1380" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Art Exhibiton</h5>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/colleagues-thinking-with-post-its-side-view_23-2149362920.jpg?t=st=1736429066~exp=1736432666~hmac=f3fd1fc15be24f34830f79f315a5ec6d87fccbe6fc84efdce5ddd530e708f5d4&w=1380" class="card-img-top" alt="Event">
                        <div class="card-body text-center">
                            <h5 class="card-title">Startup Pitch Fest</h5>
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
                <a href="/ukm/ukms" class="text-dark">View All (53)</a>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/young-child-making-diy-project-from-upcycled-materials_23-2149391046.jpg?t=st=1736428825~exp=1736432425~hmac=866d81ab1c7a09ac54f3e163b719d2b409afcb1b78a19851e4a2b1adbd02a046&w=1380" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <h5 class="card-title">Enterpreneurship Society<br><span class="fw-semibold"></span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/people-taking-part-dance-therapy-class_23-2149346550.jpg?t=st=1736428786~exp=1736432386~hmac=8410d9051cc8ad02ac178b4ea6a2b36c452f85409e916c9353263e9b3748ca27&w=1380" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <h5 class="card-title">Dance Club<br><span class="fw-semibold"></span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="https://img.freepik.com/free-photo/kid-building-robot-using-electronic-parts_23-2149357673.jpg?t=st=1736428753~exp=1736432353~hmac=a396760891adb7730be5d300cb35f63ff2e58455454e1c4c77907a28186f95bf&w=1380" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <h5 class="card-title">Robotics Club<br><span class="fw-semibold"></span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-event h-100">
                        <img src="art.jpg" class="card-img-top" alt="UKM">
                        <div class="card-body">
                            <h5 class="card-title">Art and Design Club<br><span class="fw-semibold"></span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
