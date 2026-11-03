<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0f2b46;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="{{ url('/') }}">
            <i class="bi bi-box-seam"></i>
            <span>{{ config('app.name') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            @auth
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 ms-auto">
                    <span class="navbar-text text-white-50 small">
                        {{ auth()->user()->name }}
                    </span>
                    <span class="badge {{ auth()->user()->isAdmin() ? 'text-bg-warning' : 'text-bg-info' }}">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            @else
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm" href="{{ route('register') }}">Buat Akun</a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
