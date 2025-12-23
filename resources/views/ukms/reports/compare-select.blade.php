@extends('layouts.app')

@section('title', 'Compare Events - UKMSpace')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <a href="{{ route('ukm.reports.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Back to Reports
                </a>
                <h2 class="fw-bold"><i class="bi bi-bar-chart-line"></i> Select Events to Compare</h2>
                <p class="text-muted">Choose 2-5 events to compare their performance</p>
            </div>
        </div>

        <!-- Selection Form -->
        <form action="{{ route('ukm.reports.compare') }}" method="GET">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <p class="mb-3 fw-semibold">Select events (minimum 2, maximum 5):</p>
                            @if ($events->isEmpty())
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> No events found. Create some events first.
                                </div>
                            @else
                                <div class="row">
                                    @foreach ($events as $event)
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check card p-3 h-100" style="cursor: pointer;">
                                                <input class="form-check-input event-checkbox" type="checkbox"
                                                    name="events[]" value="{{ $event->id }}"
                                                    id="event{{ $event->id }}">
                                                <label class="form-check-label w-100" for="event{{ $event->id }}"
                                                    style="cursor: pointer;">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">{{ $event->title }}</h6>
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar"></i>
                                                                {{ $event->event_date->format('M d, Y') }} |
                                                                <i class="bi bi-geo-alt"></i> {{ $event->location }}
                                                            </small>
                                                        </div>
                                                        <span class="badge bg-primary">{{ $event->registrations_count }}
                                                            reg.</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg" id="compareBtn" disabled>
                    <i class="bi bi-bar-chart-line"></i> Compare Selected Events
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
            const compareBtn = document.getElementById('compareBtn');

            function updateButtonState() {
                const checkedCount = document.querySelectorAll('.event-checkbox:checked').length;
                compareBtn.disabled = checkedCount < 2 || checkedCount > 5;

                if (checkedCount < 2) {
                    compareBtn.innerHTML = '<i class="bi bi-bar-chart-line"></i> Select at least 2 events';
                } else if (checkedCount > 5) {
                    compareBtn.innerHTML = '<i class="bi bi-bar-chart-line"></i> Maximum 5 events allowed';
                } else {
                    compareBtn.innerHTML = '<i class="bi bi-bar-chart-line"></i> Compare ' + checkedCount +
                        ' Events';
                }
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateButtonState);
            });
        });
    </script>
@endsection
