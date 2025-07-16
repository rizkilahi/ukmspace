@extends('layouts.app')

@section('title', 'Manage My Events')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">Manage My Events</h1>

    <!-- Tampilkan pesan sukses jika ada -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tombol Tambah Event -->
    <a href="{{ route('ukm.events.create') }}" class="btn btn-success mb-4">Add New Event</a>

    <!-- Daftar Event -->
    @if ($events->isEmpty())
        <p class="text-center">You have not created any events yet.</p>
    @else
        <div class="row g-4">
            @foreach ($events as $event)
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $event->image_url) }}" class="card-img-top" alt="Event Image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->title }}</h5>
                            <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                            <p class="text-muted"><strong>Date:</strong> {{ $event->event_date }}</p>
                            <p class="text-muted"><strong>Location:</strong> {{ $event->location }}</p>
                            {{-- <a href="{{ route('ukm.events.show', $event) }}" class="btn btn-primary btn-sm">View</a> --}}
                            <a href="{{ route('ukm.events.edit', $event) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('ukm.events.destroy', $event->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
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
