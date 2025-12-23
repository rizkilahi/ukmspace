@extends('layouts.app')

@section('title', 'Event Calendar - UKMSpace')

@section('content')
    <div class="container my-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="text-center">
                    <h1 class="fw-bold mb-2">
                        <i class="bi bi-calendar3 text-primary"></i> Event Calendar
                    </h1>
                    <p class="text-muted">View all upcoming events in calendar format</p>
                </div>
            </div>
        </div>

        <!-- Calendar Controls -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                            <div class="d-flex gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 15px; height: 15px; background-color: #0d6efd; border-radius: 3px;">
                                    </div>
                                    <small>Upcoming</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 15px; height: 15px; background-color: #198754; border-radius: 3px;">
                                    </div>
                                    <small>Ongoing</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 15px; height: 15px; background-color: #6c757d; border-radius: 3px;">
                                    </div>
                                    <small>Completed</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('events') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-grid-3x3-gap"></i> Grid View
                                </a>
                                <a href="{{ route('calendar') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-calendar3"></i> Calendar View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="text-center py-5" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading events...</p>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="eventDetails">
                        <!-- Event details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="viewEventBtn" class="btn btn-primary">
                        <i class="bi bi-eye"></i> View Full Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- FullCalendar CSS -->
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

        <!-- FullCalendar JS -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    height: 'auto',
                    events: function(info, successCallback, failureCallback) {
                        // Show loading
                        document.getElementById('loading').style.display = 'block';

                        fetch(`/api/calendar-events?start=${info.startStr}&end=${info.endStr}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('loading').style.display = 'none';
                                successCallback(data);
                            })
                            .catch(error => {
                                document.getElementById('loading').style.display = 'none';
                                console.error('Error fetching events:', error);
                                failureCallback(error);
                            });
                    },
                    eventClick: function(info) {
                        info.jsEvent.preventDefault();

                        const event = info.event;
                        const props = event.extendedProps;

                        // Populate modal with event details
                        document.getElementById('eventDetails').innerHTML = `
                <div class="mb-3">
                    <h6 class="text-muted small mb-1">EVENT TITLE</h6>
                    <h5 class="fw-bold">${event.title}</h5>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted small mb-1">DATE</h6>
                    <p class="mb-0">
                        <i class="bi bi-calendar3 text-primary"></i>
                        ${new Date(event.start).toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}
                    </p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted small mb-1">LOCATION</h6>
                    <p class="mb-0">
                        <i class="bi bi-geo-alt text-danger"></i>
                        ${props.location}
                    </p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted small mb-1">ORGANIZED BY</h6>
                    <p class="mb-0">
                        <i class="bi bi-building text-info"></i>
                        ${props.ukm}
                    </p>
                </div>
                <div class="mb-3">
                    <h6 class="text-muted small mb-1">STATUS</h6>
                    <span class="badge ${
                        props.status === 'upcoming' ? 'bg-primary' :
                        props.status === 'ongoing' ? 'bg-success' :
                        'bg-secondary'
                    }">
                        ${props.status.charAt(0).toUpperCase() + props.status.slice(1)}
                    </span>
                </div>
                ${props.registrations > 0 ? `
                        <div class="mb-0">
                            <h6 class="text-muted small mb-1">REGISTRATIONS</h6>
                            <p class="mb-0">
                                <i class="bi bi-people text-success"></i>
                                ${props.registrations} participant${props.registrations !== 1 ? 's' : ''}
                            </p>
                        </div>
                        ` : ''}
            `;

                        // Update view button link
                        document.getElementById('viewEventBtn').href = event.url;

                        // Show modal
                        eventModal.show();
                    },
                    eventMouseEnter: function(info) {
                        info.el.style.cursor = 'pointer';
                    },
                    loading: function(isLoading) {
                        if (isLoading) {
                            document.getElementById('loading').style.display = 'block';
                        } else {
                            document.getElementById('loading').style.display = 'none';
                        }
                    },
                    // Responsive
                    windowResize: function(view) {
                        if (window.innerWidth < 768) {
                            calendar.changeView('listWeek');
                        }
                    }
                });

                calendar.render();

                // Check initial size
                if (window.innerWidth < 768) {
                    calendar.changeView('listWeek');
                }
            });
        </script>

        <style>
            /* Custom calendar styles */
            #calendar {
                min-height: 600px;
            }

            .fc-event {
                cursor: pointer;
                transition: transform 0.2s;
            }

            .fc-event:hover {
                transform: scale(1.02);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            .fc-daygrid-event {
                padding: 2px 4px;
                font-size: 0.85rem;
            }

            .fc-button {
                text-transform: capitalize !important;
            }

            .fc-toolbar-title {
                font-size: 1.5rem !important;
                font-weight: 600 !important;
            }

            @media (max-width: 768px) {
                .fc-toolbar {
                    flex-direction: column;
                    gap: 10px;
                }

                .fc-toolbar-chunk {
                    display: flex;
                    justify-content: center;
                }
            }
        </style>
    @endpush
@endsection
