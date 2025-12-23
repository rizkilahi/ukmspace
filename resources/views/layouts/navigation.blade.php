<nav class="navbar navbar-expand-lg navbar-light bg-white py-4">
    <div class="container">
        <a class="navbar-brand fw-semibold fs-4" href="/">UKM Space</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto fw-semibold">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('ukms') }}">UKM</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('events') }}">Event</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('calendar') }}">Calendar</a></li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contact Us</a></li>
            </ul>
            <div class="d-flex align-items-center">
                @auth
                    <!-- Menu Dropdown Profil -->
                    <div class="dropdown">
                        <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                            <!-- Role-based Menu -->
                            @if (auth()->user()->role === 'ukm')
                                <li><a class="dropdown-item" href="{{ route('ukms.profile') }}">
                                        <i class="bi bi-building"></i> UKM Profile
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('ukm.analytics') }}">
                                        <i class="bi bi-bar-chart-fill"></i> Analytics
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('ukm.reports.index') }}">
                                        <i class="bi bi-file-earmark-bar-graph"></i> Reports
                                    </a></li>
                            @endif
                            @if (auth()->user()->role === 'user')
                                <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.myEvents') }}">
                                        <i class="bi bi-calendar-check"></i> My Events
                                    </a></li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="/logout" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" id="open-modal" class="btn fw-medium me-2">LOGIN</a>
                    <a href="{{ route('register') }}" class="btn fw-medium" style="background: #C4C4C4;">SIGNUP</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
