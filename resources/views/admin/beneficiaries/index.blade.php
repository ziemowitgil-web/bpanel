@extends('layouts.app')

@section('content')
    <div class="container py-5">

        {{-- Nagłówek --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Panel beneficjentów</h1>
            <a href="{{ route('admin.beneficiaries.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Dodaj nowego
            </a>
        </div>

        {{-- Komunikaty --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                @if(session('user_email') && session('user_password'))
                    <button type="button" class="btn btn-sm btn-outline-primary ms-3" data-bs-toggle="modal" data-bs-target="#credentialsModal">
                        Zobacz dane logowania
                    </button>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabela beneficjentów --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imię i nazwisko</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Aktywny</th>
                            <th>Link do zajęć</th>
                            <th>Slug</th>
                            <th class="text-center">Akcje</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($beneficiaries as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td>{{ $b->first_name }} {{ $b->last_name }}</td>
                                <td>{{ $b->email }}</td>
                                <td>{{ $b->phone ?? '-' }}</td>
                                <td>
                                    @if($b->active)
                                        <span class="badge bg-success">TAK</span>
                                    @else
                                        <span class="badge bg-secondary">NIE</span>
                                    @endif
                                </td>
                                <td>
                                    @if($b->class_link)
                                        <a href="{{ $b->class_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-link-45deg"></i> Zajęcia
                                        </a>
                                    @else
                                        <span class="text-muted small">brak</span>
                                    @endif
                                </td>
                                <td><code>{{ $b->slug }}</code></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        <a href="{{ route('admin.beneficiaries.edit', $b) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edytuj
                                        </a>

                                        <form action="{{ route('admin.beneficiaries.destroy', $b) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Na pewno chcesz usunąć?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Usuń
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.beneficiaries.welcome-mail', $b->id) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="bi bi-envelope"></i> Wyślij mail powitalny
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    Brak beneficjentów do wyświetlenia.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal z danymi logowania --}}
    @if(session('user_email') && session('user_password'))
        <div class="modal fade" id="credentialsModal" tabindex="-1" aria-labelledby="credentialsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="credentialsModalLabel">Dane logowania beneficjenta</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Login (email):</strong> <span id="modalLogin">{{ session('user_email') }}</span></p>
                        <p><strong>Hasło:</strong> <span id="modalPassword">{{ session('user_password') }}</span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        <button type="button" class="btn btn-primary" onclick="copyCredentials()">
                            <i class="bi bi-clipboard"></i> Kopiuj dane
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function copyCredentials() {
                const login = document.getElementById('modalLogin').textContent;
                const password = document.getElementById('modalPassword').textContent;
                const textToCopy = `Login: ${login}\nHasło: ${password}`;

                navigator.clipboard.writeText(textToCopy).then(() => {
                    alert('Dane logowania skopiowane do schowka!');
                }).catch(err => {
                    console.error('Błąd kopiowania: ', err);
                });
            }
        </script>
    @endif
@endsection
