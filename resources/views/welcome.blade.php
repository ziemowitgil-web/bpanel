@extends('layouts.app')

@section('content')
    <div class="container mt-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="text-center">
            <i class="bi bi-tools display-1 text-warning mb-3"></i>
            <h1 class="mb-3">Panel w budowie</h1>
            <p class="fs-5">
                Witaj! Obecnie system dla beneficjentów służy tylko do generowania przekierowań na zajęcia.
            </p>
            <p class="text-muted mb-4">
                Panel zostanie wkrótce rozbudowany o nowe funkcje.
            </p>
            <i class="bi bi-arrow-repeat display-2 text-secondary mb-4"></i>

            <div class="alert alert-info mt-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                Uwaga! <strong>12.11</strong> nastąpi zmiana domeny z
                <strong>feer-kr.edu.pl</strong> na <strong>edukacja.cloud</strong>.
                Wraz z tym zmianie ulegną również <strong>loginy do Microsoft 365 dla edukacji</strong> 😊
            </div>
        </div>
    </div>
@endsection
