@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Beneficjenci</h1>
        <a href="{{ route('admin.beneficiaries.create') }}" class="btn btn-primary mb-3">Dodaj nowego</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}

                @if(session('user_email') && session('user_password'))
                    <hr>
                    <div><strong>Email (login):</strong> {{ session('user_email') }}</div>
                    <div><strong>Hasło:</strong> {{ session('user_password') }}</div>
                @endif
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Aktywny</th>
                    <th>Link do zajęć</th>
                    <th>Login</th>
                    <th>Slug</th>
                    <th>Akcje</th>
                </tr>
                </thead>
                <tbody>
                @foreach($beneficiaries as $b)
                    <tr>
                        <td>{{ $b->id }}</td>
                        <td>{{ $b->first_name }}</td>
                        <td>{{ $b->last_name }}</td>
                        <td>{{ $b->email }}</td>
                        <td>{{ $b->active ? 'Tak' : 'Nie' }}</td>
                        <td>
                            @if($b->class_link)
                                <a href="{{ $b->class_link }}" target="_blank">Link</a>
                            @endif
                        </td>
                        <td>{{ $b->user ? $b->user->email : '-' }}</td>
                        <td>{{ $b->slug }}</td>
                        <td>
                            <a href="{{ route('admin.beneficiaries.edit', $b) }}" class="btn btn-sm btn-warning mb-1">Edytuj</a>

                            <form action="{{ route('admin.beneficiaries.destroy', $b) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Na pewno chcesz usunąć?')">Usuń</button>
                            </form>

                            @if($b->user)
                                <a href="{{ route('admin.beneficiaries.welcome-mail', $b) }}"
                                   class="btn btn-sm btn-primary mb-1"
                                   onclick="return confirm('Wyślij mail powitalny do {{ $b->first_name }}?')">
                                    Wyślij mail powitalny
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
