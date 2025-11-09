@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-4 mb-3 text-primary">Witamy w panelu eduk@cja!</h1>
            <p class="fs-5 mb-4">
                Cieszymy się, że jesteś z nami. W panelu znajdziesz wszystkie potrzebne narzędzia do obsługi kursów i zajęć.
            </p>

            <h4 class="mb-3">Funkcje panelu:</h4>
            <ul class="text-start mx-auto" style="max-width: 400px;">
                <li>Aktywacja konta Microsoft 365 dla Edukacji</li>
                <li>Licencje specjalnie dla naszych kursantów</li>
                <li>Linki do zajęć i materiały edukacyjne (dostępne w zależności od kursu)</li>
                <li>Zarządzanie środowiskiem testowym</li>
                <li>Rozliczenia płatności i inne funkcje administracyjne</li>
            </ul>

            <a href="{{ env('APP_URL') }}/login" class="btn btn-lg btn-primary mt-3">Zaloguj się do panelu</a>

            <p class="text-muted mt-4">
                Zespół FEER – Fundacja Edukacji Empatii i Rozwoju
            </p>

            <div class="alert alert-warning mt-4">
                <strong>Uwaga!</strong><br>
                Od <strong>12.11</strong> zmieni się domena z <strong>feer-kr.edu.pl</strong> na <strong>edukacja.cloud</strong>.
                Loginy do Microsoft 365 dla edukacji również ulegną zmianie.
            </div>
        </div>
    </div>
@endsection
