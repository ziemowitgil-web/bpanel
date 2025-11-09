<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'System') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 65px;
        }

        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .btn {
            border-radius: 4px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .alert {
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            body { padding-top: 60px; }
            .navbar-brand { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
<div id="app">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'System') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @php
                            $user = Auth::user();
                            $beneficiary = $user->is_admin ? null : $user->beneficiary;
                        @endphp
                        <li class="nav-item dropdown text-end">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ $beneficiary->first_name ?? $user->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2 small text-muted">
                                    Konto: {{ $user->name }}<br>
                                    Email: {{ $user->email }}<br>
                                    @if($beneficiary)
                                        Beneficjent: {{ $beneficiary->first_name }} {{ $beneficiary->last_name }}
                                    @endif
                                </li>
                                @if($user->is_admin)
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.beneficiaries.index') }}">
                                            <i class="bi bi-gear me-1"></i> Panel Admina
                                        </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> Wyloguj
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="container py-4">
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
