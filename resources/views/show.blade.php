@extends('layouts.app')

@section('title', 'Event Details')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">{{ $event->title }}</h1>

    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $event->image_url) }}" class="img-fluid" alt="Event Image">
        </div>
        <div class="col-md-6">
            <h2>Description</h2>
            <p>{{ $event->description }}</p>

            <h4>Date: <span class="text-muted">{{ $event->event_date }}</span></h4>
            <h4>Location: <span class="text-muted">{{ $event->location }}</span></h4>

            @auth
            @php
                $registration = $event->registrations->where('user_id', auth()->id())->first();
                $role = auth()->user()->role;
            @endphp

            @if ($role === 'ukm')
                <!-- Tidak ada tombol untuk UKM -->
                <p class="text-muted"></p>
            @elseif ($registration)
                <!-- Jika sudah terdaftar, tampilkan tombol tidak aktif -->
                <button type="button" class="btn btn-secondary" disabled>Registered</button>
            @else
                <!-- Jika belum terdaftar, tampilkan tombol Register -->
                <form action="{{ route('events.register', $event) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-success">Register for Event</button>
                </form>
            @endif
        @else
            <!-- Tampilkan tombol login untuk pengguna yang belum login -->
            <a href="{{ route('login') }}" class="btn btn-primary">Log In to Register</a>
        @endauth

        </div>
    </div>
</div>
@endsection
