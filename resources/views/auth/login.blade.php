@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>Logowanie</h4>
                    </div>

                    <div class="card-body p-4">
                        <p class="text-center text-muted mb-4">
                            Wprowadź swoje dane, aby uzyskać dostęp do panelu.
                        </p>

                        <form method="POST" action="{{ route('login') }}">
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

                            <!-- Hasło -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Hasło</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input id="password" type="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           required>
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Zapamiętaj mnie -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Zapamiętaj mnie</label>
                            </div>

                            <!-- Przyciski -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Zaloguj się
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                        Nie pamiętasz hasła?
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center text-muted small">
                        © {{ date('Y') }} FEER - Fundacja Edukacji Empatii i Rozwoju
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
