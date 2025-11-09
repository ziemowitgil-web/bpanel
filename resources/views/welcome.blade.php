@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
            <div class="col-md-8 text-center">
                {{-- Baner FEER --}}
                <img src="https://feer.org.pl/files/ban/banner-1.png" alt="FEER – Fundacja Edukacji Empatii i Rozwoju" class="img-fluid mb-4" style="max‑width: 100%; height: auto;">

                <h1 class="display-4 text-primary mb-3">Witamy na platformie eduk@cja!</h1>
                <p class="fs-5 mb-4">
                    Cieszymy się, że dołączyłeś/dołączyłaś do naszego panelu. Tutaj znajdziesz linki do zajęć, materiały edukacyjne, możliwość aktywacji konta Microsoft 365 dla edukacji oraz licencje specjalnie dla naszych kursantów.
                </p>

                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Stan panelu</h5>
                        <p class="card-text">
                            Panel jest wciąż w rozbudowie, ale możesz już korzystać z najważniejszych funkcji: generowania linków do zajęć oraz aktywacji kont Microsoft 365.
                        </p>
                    </div>
                </div>

                <div class="alert alert-warning d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle me-2 fs-3"></i>
                    <div>
                        <strong>Uwaga!</strong><br>
                        Od <strong>12.11</strong> zmieni się domena z <strong>feer-kr.edu.pl</strong> na <strong>edukacja.cloud</strong>.<br>
                        Loginy do Microsoft365 dla edukacji również ulegną zmianie.
                    </div>
                </div>

                <a href="{{ env('APP_URL') }}/login" class="btn btn-primary btn-lg mt-3">Przejdź do panelu</a>

                <p class="text-muted mt-4">
                    Zespół FEER – Fundacja Edukacji Empatii i Rozwoju
                </p>
            </div>
        </div>
    </div>
@endsection
