@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="container my-5">
        <h1 class="text-center mb-4">Edit Event</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('ukm.events.update', $event->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Event Title -->
            <div class="form-group mb-3">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" class="form-control"
                    value="{{ old('title', $event->title) }}" required>
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Event Description -->
            <div class="form-group mb-3">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="5" required>{{ old('description', $event->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Event Location -->
            <div class="form-group mb-3">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" class="form-control"
                    value="{{ old('location', $event->location) }}" required>
                @error('location')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Event Date -->
            <div class="form-group mb-3">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" class="form-control"
                    value="{{ old('event_date', $event->event_date) }}" required>
                @error('event_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Event Photo -->
            <div class="form-group mb-3">
                <label for="image">Event Photo</label>
                @if ($event->image_url)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $event->image_url) }}" alt="Event Photo"
                            style="max-width: 200px; max-height: 150px;">
                    </div>
                @endif
                <input type="file" id="image" name="image" class="form-control">
                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="{{ route('ukm.events.index') }}" class="btn btn-secondary">Cancel</a>
        </form>

    </div>
@endsection
