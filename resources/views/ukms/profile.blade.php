@extends('layouts.app')

@section('title', 'UKM Profile - UKM Space')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">UKM Profile</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>{{ $ukm->name }}</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Email:</strong>
                <p>{{ $ukm->email }}</p>
            </div>
            <div class="mb-3">
                <strong>Phone:</strong>
                <p>{{ $ukm->phone ?? 'N/A' }}</p>
            </div>
            <div class="mb-3">
                <strong>Address:</strong>
                <p>{{ $ukm->address ?? 'N/A' }}</p>
            </div>
            <div class="mb-3">
                <strong>Website:</strong>
                <p>
                    @if ($ukm->website)
                        <a href="{{ $ukm->website }}" target="_blank">{{ $ukm->website }}</a>
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div class="mb-3">
                <strong>Description:</strong>
                <p>{{ $ukm->description ?? 'N/A' }}</p>
            </div>
            <div class="mb-3">
                <strong>Verification Status:</strong>
                <p class="badge {{ $ukm->verification_status === 'active' ? 'bg-success' : 'bg-warning' }}">
                    {{ ucfirst($ukm->verification_status) }}
                </p>
            </div>
            <div class="mb-3">
                <strong>Logo:</strong>
                @if ($ukm->logo)
                    <div>
                        <img src="{{ asset('storage/' . $ukm->logo) }}" alt="Logo" style="max-width: 150px; max-height: 150px;">
                    </div>
                @else
                    <p>No logo uploaded</p>
                @endif
            </div>
            <div>
                <a href="{{ route('ukm.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection
