@extends('layouts.app')

@section('title', 'Create New Event')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">Create New Event</h1>

    <!-- Tampilkan pesan error jika ada -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('ukm.events.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" id="location" name="location" class="form-control" value="{{ old('location') }}" required>
        </div>

        <!-- Event Date -->
        <div class="mb-3">
            <label for="date" class="form-label">Event Date</label>
            <input type="date" id="date" name="event_date" class="form-control" value="{{ old('event_date') }}" required>
        </div>

        <!-- Image Upload -->
        <div class="mb-3">
            <label for="image_url" class="form-label">Event Image</label>
            <input type="file" id="image_url" name="image_url" class="form-control" accept="image/*">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">Create Event</button>
        <a href="{{ route('ukm.events.manage') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
