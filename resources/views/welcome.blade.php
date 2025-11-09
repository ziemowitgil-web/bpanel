@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row align-items-center">
            <!-- Lewa kolumna: tekst -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 text-primary mb-3">Panel eduk@cja</h1>
                <p class="fs-5 mb-4">
                    Panel udostępnia wszystkie niezbędne narzędzia dla kursantów i administratorów.
                </p>

                <h4 class="mb-3">Funkcje panelu:</h4>
                <ul>
                    <li>Aktywacja konta Microsoft 365 dla Edukacji</li>
                    <li>Licencje specjalnie dla kursantów</li>
                    <li>Linki do zajęć i materiały edukacyjne (w zależności od kursu)</li>
                    <li>Zarządzanie środowiskiem testowym</li>
                    <li>Rozliczenia płatności i funkcje administracyjne</li>
                    <li>Zarządzanie kontem PE2 (PE2 w trakcie prac, po awarii planowane uruchomienie w przyszłym tygodniu)</li>
                </ul>

                <a href="{{ env('APP_URL') }}/login" class="btn btn-primary btn-lg mt-3">Zaloguj się do panelu</a>

                <div class="alert alert-warning mt-4">
                    <strong>Uwaga!</strong><br>
                    Od <strong>12.11</strong> zmieni się domena z <strong>feer-kr.edu.pl</strong> na <strong>edukacja.cloud</strong>.
                    Loginy do Microsoft 365 dla edukacji również ulegną zmianie.
                </div>
            </div>

            <!-- Prawa kolumna: grafika -->
            <div class="col-lg-6 text-center">
                <img src="https://feer.org.pl/files/ban/banner-1.png?ts=1640251163"
                     alt="FEER – Fundacja Edukacji Empatii i Rozwoju"
                     class="img-fluid rounded shadow">
            </div>
        </div>

        <p class="text-muted text-center mt-5">
            Zespół FEER – Fundacja Edukacji Empatii i Rozwoju
        </p>
    </div>
@endsection
