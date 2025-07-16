@extends('layouts.app')

@section('title', 'Edit UKM Profile - UKM Space')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Edit UKM Profile</h1>

    <form method="POST" action="{{ route('ukms.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="name" class="form-label">UKM Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $ukm->name) }}" required>
            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $ukm->email) }}" readonly>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $ukm->phone) }}">
            @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $ukm->address) }}">
            @error('address')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="url" id="website" name="website" class="form-control" value="{{ old('website', $ukm->website) }}">
            @error('website')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5">{{ old('description', $ukm->description) }}</textarea>
            @error('description')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" id="logo" name="logo" class="form-control">
            @if ($ukm->logo)
                <img src="{{ asset('storage/' . $ukm->logo) }}" alt="Logo" style="max-width: 150px; max-height: 150px; margin-top: 10px;">
            @endif
            @error('logo')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
