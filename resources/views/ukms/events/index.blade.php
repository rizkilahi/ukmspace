@extends('layouts.app')

@section('title', 'All Events')

@section('content')
    <div class="container my-5">
        <h1 class="mb-4 text-center">All Events</h1>
        <a href="{{ route('ukm.events.index') }}" class="btn btn-success mb-4">Manage My Event Event</a>

        <!-- Tampilkan pesan sukses jika ada -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Daftar Event -->
        @if ($events->isEmpty())
            <p class="text-center">No events found.</p>
        @else
            <div class="row g-4">
                @foreach ($events as $event)
                    <div class="col-md-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $event->image_url) }}" class="card-img-top" alt="Event Image">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->title }}</h5>
                                <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                <p class="text-muted"><strong>Date:</strong> {{ $event->date }}</p>
                                <p class="text-muted"><strong>Location:</strong> {{ $event->location }}</p>
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-sm">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
