@extends('layouts.app')

@section('title', 'Profile - UKM Space')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Profile</h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.delete-user-form')
        </div>
        <div class="col-md-6 mb-4">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>
@endsection
