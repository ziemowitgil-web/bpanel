@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Powitanie -->
        <div class="text-center mb-4">
            <h1 class="h3 fw-semibold">Panel beneficjenta</h1>
            <p class="text-muted">Witaj, {{ $beneficiary->first_name }}! — Twój panel dostępu do zajęć i zasobów systemu</p>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="dashboardTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab" aria-controls="classes" aria-selected="true">
                    <i class="bi bi-play-circle-fill"></i> Zajęcia
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tools-tab" data-bs-toggle="tab" data-bs-target="#tools" type="button" role="tab" aria-controls="tools" aria-selected="false">
                    <i class="bi bi-windows"></i> Microsoft365 / PE
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="technical-tab" data-bs-toggle="tab" data-bs-target="#technical" type="button" role="tab" aria-controls="technical" aria-selected="false">
                    <i class="bi bi-info-circle"></i> Dane techniczne
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="instructor-tab" data-bs-toggle="tab" data-bs-target="#instructor" type="button" role="tab" aria-controls="instructor" aria-selected="false">
                    <i class="bi bi-person-badge"></i> Prowadzący
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="licenses-tab" data-bs-toggle="tab" data-bs-target="#licenses" type="button" role="tab" aria-controls="licenses" aria-selected="false">
                    <i class="bi bi-key"></i> Licencje
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vps-tab" data-bs-toggle="tab" data-bs-target="#vps" type="button" role="tab" aria-controls="vps" aria-selected="false">
                    <i class="bi bi-hdd-network"></i> VPS / środowisko testowe
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="institution-tab" data-bs-toggle="tab" data-bs-target="#institution" type="button" role="tab" aria-controls="institution" aria-selected="false">
                    <i class="bi bi-building"></i> Instytucja
                </button>
            </li>
        </ul>

        <!-- Treść zakładek -->
        <div class="tab-content" id="dashboardTabsContent">
            <!-- Zajęcia -->
            <div class="tab-pane fade show active" id="classes" role="tabpanel">
                <div class="card border-danger shadow-sm mb-3">
                    <div class="card-body text-center">
                        @if($beneficiary->active && $beneficiary->class_link)
                            <p class="lead">Dołącz do zajęć online:</p>
                            <a href="{{ $beneficiary->class_link }}" target="_blank" class="btn btn-danger btn-lg mb-2" role="button">
                                <i class="bi bi-box-arrow-in-right"></i> Wejdź na zajęcia
                            </a>
                            <p><small>Alternatywny link: <a href="{{ url('/meet/'.$beneficiary->slug) }}" target="_blank">{{ url('/meet/'.$beneficiary->slug) }}</a></small></p>
                        @elseif(!$beneficiary->active)
                            <p class="text-muted">Twoje konto nieaktywne – brak dostępu do zajęć</p>
                        @else
                            <p class="text-muted">Brak linku do zajęć</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Microsoft 365 / PE -->
            <div class="tab-pane fade" id="tools" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-info shadow-sm">
                            <div class="card-header bg-info text-white">
                                <i class="bi bi-windows"></i> Microsoft 365 Edu
                            </div>
                            <div class="card-body text-center">
                                <p><strong>Login:</strong> {{ strtolower($beneficiary->first_name) }}.{{ strtolower($beneficiary->last_name) }}@feer-kr.edu.pl</p>
                                <a href="https://www.office.com" target="_blank" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right"></i> Zaloguj się
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-warning shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <i class="bi bi-book"></i> Platforma PE
                            </div>
                            <div class="card-body text-center">
                                <p>Login i hasło takie samo jak Microsoft365</p>
                                <a href="https://www.pe.feer.org.pl" target="_blank" class="btn btn-warning text-dark">
                                    <i class="bi bi-box-arrow-in-right"></i> Zaloguj się
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dane techniczne -->
            <div class="tab-pane fade" id="technical" role="tabpanel">
                <div class="card border-secondary shadow-sm">
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>SID:</strong> {{ $beneficiary->id }}</li>
                            <li><strong>Imię:</strong> {{ $beneficiary->first_name }}</li>
                            <li><strong>Nazwisko:</strong> {{ $beneficiary->last_name }}</li>
                            <li><strong>Email:</strong> {{ $beneficiary->email }}</li>
                            <li><strong>Telefon:</strong> {{ $beneficiary->phone }}</li>
                            <li><strong>Data utworzenia:</strong> {{ $beneficiary->created_at->format('d.m.Y H:i') }}</li>
                            <li><strong>Status:</strong>
                                @if($beneficiary->active)
                                    <span class="badge bg-success">Aktywny</span>
                                @else
                                    <span class="badge bg-secondary">Nieaktywny</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Prowadzący -->
            <div class="tab-pane fade" id="instructor" role="tabpanel">
                <div class="card border-dark shadow-sm">
                    <div class="card-body">
                        @if($beneficiary->instructor)
                            <p><strong>Imię i nazwisko:</strong> {{ $beneficiary->instructor->first_name }} {{ $beneficiary->instructor->last_name }}</p>
                            <p><strong>Email:</strong> <a href="mailto:{{ $beneficiary->instructor->email }}">{{ $beneficiary->instructor->email }}</a></p>
                            <p><strong>Telefon:</strong> {{ $beneficiary->instructor->phone }}</p>
                        @else
                            <p class="text-muted">Nie przypisano prowadzącego.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Licencje -->
            <div class="tab-pane fade" id="licenses" role="tabpanel">
                <div class="card border-success shadow-sm">
                    <div class="card-body">
                        @if($beneficiary->licenses->isEmpty())
                            <p class="text-muted">Nie przypisano żadnych licencji.</p>
                        @else
                            <ul class="list-group">
                                @foreach($beneficiary->licenses as $license)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $license->name }}
                                        <span class="badge bg-primary rounded-pill">{{ $license->type }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- VPS / środowisko testowe -->
            <div class="tab-pane fade" id="vps" role="tabpanel">
                <div class="card border-success shadow-sm">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-hdd-network"></i> Środowisko testowe VPS
                    </div>
                    <div class="card-body">
                        <p>W tym miejscu będzie można zarządzać Twoim serwerem testowym <strong>VPS</strong>.</p>
                        <p><strong>Adres serwera:</strong> <code>vps-{{ $user->id }}.feer-kr.edu.pl</code></p>
                        <p><strong>Status:</strong> <span class="text-success fw-semibold">W przygotowaniu</span></p>
                        <div class="mt-3">
                            <button class="btn btn-outline-success" disabled>
                                <i class="bi bi-plug"></i> Połącz z serwerem
                            </button>
                        </div>
                        <hr>
                        <p class="text-muted small mb-0">W przyszłości będzie tu możliwość: restartu instancji, monitorowania zasobów i dostępu SSH.</p>
                    </div>
                </div>
            </div>

            <!-- Instytucja -->
            <div class="tab-pane fade" id="institution" role="tabpanel">
                <div class="card border-info shadow-sm">
                    <div class="card-body">
                        <p><strong>FUNDACJA EDUKACJI EMPATII ROZWOJU „FEER”</strong></p>
                        <p>ul. Władysława Barbackiego 28/18, 33-300 Nowy Sącz</p>
                        <p><strong>Koordynator szkoleń:</strong> Ewelina Frączek (<a href="mailto:ewelina.fraczek@feer.org.pl">ewelina.fraczek@feer.org.pl</a>)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
