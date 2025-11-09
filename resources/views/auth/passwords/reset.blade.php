@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 400px; width: 100%;">
            <div class="card-header text-center bg-primary text-white rounded-top-4 py-3">
                <h4 class="mb-0"><i class="bi bi-envelope-fill me-2"></i>Reset Hasła</h4>
            </div>

            <div class="card-body p-4">
                <p class="text-center text-muted mb-4 fs-6">
                    Wprowadź adres e-mail przypisany do Twojego konta.
                    Wyślemy Ci link do zmiany hasła.
                </p>

                @if (session('status'))
                    <div class="alert alert-success text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Adres e-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input id="email" type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Przycisk -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            <i class="bi bi-arrow-right-circle me-2"></i>Wyślij link do resetu hasła
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center text-muted small bg-light rounded-bottom-4">
                © {{ date('Y') }} FEER – Fundacja Edukacji Empatii i Rozwoju |
                <a href="https://feer.org.pl/rodo" class="text-decoration-none">RODO</a>
            </div>
        </div>
    </div>
@endsection
