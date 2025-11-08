<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { min-height: 100vh; padding-top: 70px; background-color: #f8f9fa; }
        .navbar-brand { font-weight: 600; }
        .dropdown-header-info { font-size: 0.875rem; padding: 0.5rem 1rem; border-top: 1px solid #e9ecef; }
    </style>
</head>
<body>
<div id="app">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Lewa strona menu -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door me-1"></i> Dashboard
                        </a>
                    </li>
                    @if(Auth::check() && Auth::user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.beneficiaries.index') }}">
                                <i class="bi bi-person-gear me-1"></i> Panel Admina
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Prawa strona menu -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @guest
                        @if(Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                </a>
                            </li>
                        @endif
                        @if(Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="bi bi-person-plus me-1"></i> Register
                                </a>
                            </li>
                        @endif
                    @else
                        @php
                            $user = Auth::user();
                            $beneficiary = $user->is_admin
                                ? \App\Models\Beneficiary::where('email', 'system@feer.org.pl')->first()
                                : $user->beneficiary;
                        @endphp

                        <li class="nav-item dropdown text-end">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ $beneficiary->first_name ?? 'BRAK' }} {{ $beneficiary->last_name ?? '' }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> Wyloguj
                                    </a>
                                </li>

                                <li>
                                    <div class="dropdown-header-info">
                                        <div><i class="bi bi-person-badge me-1"></i> Konto: {{ $user->name }}</div>
                                        <div><i class="bi bi-envelope me-1"></i> Email: {{ $user->email }}</div>
                                        <div><i class="bi bi-people me-1"></i>
                                            Beneficjent: {{ $beneficiary->first_name ?? '-' }} {{ $beneficiary->last_name ?? '-' }}
                                        </div>
                                    </div>
                                </li>

                                @if($user->is_admin)
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.beneficiaries.index') }}">
                                            <i class="bi bi-gear me-1"></i> Panel Admina
                                        </a>
                                    </li>
                                @endif
                            </ul>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Główna zawartość -->
    <main class="py-4 container">
        @yield('content')
    </main>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
