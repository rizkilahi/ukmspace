@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome, {{ Auth::user()->name }}</h1>
    <h2>Your Events</h2>
    <ul>
        @foreach ($events as $event)
            <li>{{ $event->title }} on {{ $event->event_date }}</li>
        @endforeach
    </ul>
</div>
@endsection
