@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Panel Beneficjentów</h1>
            <a href="{{ route('admin.beneficiaries.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Dodaj beneficjenta
            </a>
        </div>

        {{-- Wiadomości o sukcesie lub błędzie --}}
        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                <strong>{{ session('success') }}</strong>
                @if(session('user_email') && session('user_password'))
                    <div class="mt-3 p-2 bg-light border rounded small">
                        <div><strong>Login:</strong> {{ session('user_email') }}</div>
                        <div><strong>Hasło:</strong> {{ session('user_password') }}</div>
                    </div>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabela beneficjentów --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imię i nazwisko</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Aktywny</th>
                            <th>Link do zajęć</th>
                            <th>Slug</th>
                            <th style="width: 260px;">Akcje</th>
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
                                    @if($b->active)
                                        <span class="badge bg-success">TAK</span>
                                    @else
                                        <span class="badge bg-secondary">NIE</span>
                                    @endif
                                </td>
                                <td>
                                    @if($b->class_link)
                                        <a href="{{ $b->class_link }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="bi bi-link-45deg"></i> Zajęcia
                                        </a>
                                    @else
                                        <span class="text-muted small">brak</span>
                                    @endif
                                </td>
                                <td><code>{{ $b->slug }}</code></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('admin.beneficiaries.edit', $b) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i> Edytuj
                                        </a>

                                        <form action="{{ route('admin.beneficiaries.destroy', $b) }}" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Usuń
                                            </button>
                                        </form>

                                        @if($b->user)
                                            <form action="{{ route('admin.beneficiaries.sendWelcomeMail', $b->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-envelope"></i> Mail powitalny
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
    </div>
@endsection
