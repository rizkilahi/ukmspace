@extends('layouts.app')

@section('title', 'All Events')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">All Events</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @foreach ($events as $event)
            <div class="col-md-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $event->image_url) }}" class="card-img-top" alt="Event Image">
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                        <p class="text-muted"><strong>Date:</strong> {{ $event->event_date }}</p>
                        <p class="text-muted"><strong>Location:</strong> {{ $event->location }}</p>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-primary">View Details</a>

                        @auth
                            @php
                                $isRegistered = $event->registrations->where('user_id', auth()->id())->first();
                            @endphp

                            @if ($isRegistered)

                                <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#statusModal{{ $event->id }}">
                                    View Status
                                </button>

                                <!-- Status Modal -->
                                <div class="modal fade" id="statusModal{{ $event->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $event->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="statusModalLabel{{ $event->id }}">Event Status</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Event:</strong> {{ $event->title }}</p>
                                                <p><strong>Date:</strong> {{ $event->event_date }}</p>
                                                <p><strong>Location:</strong> {{ $event->location }}</p>
                                                <p><strong>Status:</strong> {{ $isRegistered->status }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Status Modal -->
                            @else
                                <!-- Trigger Register Modal -->
                                <button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#registerModal{{ $event->id }}">
                                    Register
                                </button>

                                <!-- Register Modal -->
                                <div class="modal fade" id="registerModal{{ $event->id }}" tabindex="-1" aria-labelledby="registerModalLabel{{ $event->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="registerModalLabel{{ $event->id }}">Confirm Registration</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Event:</strong> {{ $event->title }}</p>
                                                <p><strong>Date:</strong> {{ $event->event_date }}</p>
                                                <p><strong>Location:</strong> {{ $event->location }}</p>
                                                <hr>
                                                <p><strong>User:</strong> {{ auth()->user()->name }}</p>
                                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                                <p><strong>Phone:</strong> {{ auth()->user()->phone }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('events.register', $event) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Confirm Registration</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Register Modal -->
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary mt-2">Log In to Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection
