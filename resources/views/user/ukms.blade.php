@extends('layouts.app')

@section('title', 'All UKMs')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 text-center">All UKMs</h1>

    @if ($ukms->isEmpty())
        <p class="text-center">No UKMs found.</p>
    @else
        <div class="row g-4">
            @foreach ($ukms as $ukm)
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $ukm->logo) }}" class="card-img-top" alt="UKM Logo">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ukm->name }}</h5>
                            <p class="card-text">{{ Str::limit($ukm->description, 100) }}</p>
                            <a href="{{ route('ukmIs.events', $ukm->id) }}" class="btn btn-primary btn-sm">View Events</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $ukms->links() }}
        </div>
    @endif
</div>
@endsection
