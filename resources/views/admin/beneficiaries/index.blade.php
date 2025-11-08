@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Panel beneficjentów</h1>
            <a href="{{ route('admin.beneficiaries.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Dodaj nowego
            </a>
        </div>

        {{-- komunikaty --}}
        @if(session('success') || session('info') || session('error'))
            @php
                $alertType = session('success') ? 'success' : (session('info') ? 'info' : 'danger');
                $message = session('success') ?? session('info') ?? session('error');
            @endphp
            <div class="alert alert-{{ $alertType }} alert-dismissible fade show shadow-sm" role="alert">
                <strong>{{ $message }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- tabela --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imię i nazwisko</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Aktywny</th>
                            <th>Link do zajęć</th>
                            <th>Slug</th>
                            <th>Akcje</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($beneficiaries as $b)
                            <tr>
                                <td>{{ $b->id }}</td>
                                <td><strong>{{ $b->first_name }} {{ $b->last_name }}</strong></td>
                                <td>{{ $b->email }}</td>
                                <td>{{ $b->phone ?? '-' }}</td>
                                <td>
                                <span class="badge {{ $b->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $b->active ? 'TAK' : 'NIE' }}
                                </span>
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
                                    <div class="d-flex flex-wrap gap-1">
                                        {{-- Edycja --}}
                                        <a href="{{ route('admin.beneficiaries.edit', $b) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edytuj
                                        </a>

                                        {{-- Usuwanie beneficjenta --}}
                                        <form action="{{ route('admin.beneficiaries.destroy', $b) }}" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Usuń
                                            </button>
                                        </form>

                                        {{-- Mail powitalny --}}
                                        <form action="{{ route('admin.beneficiaries.welcome-mail', $b->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="bi bi-envelope"></i> Wyślij mail powitalny
                                            </button>
                                        </form>

                                        {{-- Usuń konto użytkownika --}}
                                        @if($b->user)
                                            <form action="{{ route('admin.beneficiaries.delete-user', $b->id) }}" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć konto użytkownika?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-person-x"></i> Usuń konto
                                                </button>
                                            </form>
                                        @endif
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

        {{-- Modal do wyświetlania loginu i hasła --}}
        @if(session('user_email') && session('user_password'))
            <div class="modal fade" id="credentialsModal" tabindex="-1" aria-labelledby="credentialsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="credentialsModalLabel">Dane logowania beneficjenta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Login (email):</strong> {{ session('user_email') }}</p>
                            <p><strong>Hasło:</strong> {{ session('user_password') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection

@section('scripts')
    @if(session('user_email') && session('user_password'))
        <script>
            var credentialsModal = new bootstrap.Modal(document.getElementById('credentialsModal'));
            credentialsModal.show();
        </script>
    @endif
@endsection
