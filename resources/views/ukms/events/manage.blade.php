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

        <!-- Action Buttons -->
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('ukm.events.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New Event
            </a>
            <a href="{{ route('ukm.analytics') }}" class="btn btn-primary">
                <i class="bi bi-bar-chart-fill"></i> View Analytics
            </a>
            <a href="{{ route('ukm.reports.index') }}" class="btn btn-info">
                <i class="bi bi-file-earmark-bar-graph"></i> Advanced Reports
            </a>
        </div>

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
                                <p class="text-muted">
                                    <strong>Registrations:</strong>
                                    <span class="badge bg-primary">{{ $event->registrations_count ?? 0 }}</span>
                                </p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('ukm.events.registrations', $event) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-people"></i> Registrations
                                    </a>
                                    <a href="{{ route('ukm.events.qr-code', $event) }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-qr-code"></i> QR Code
                                    </a>
                                    <a href="{{ route('ukm.events.edit', $event) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('ukm.events.destroy', $event->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this event?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
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
