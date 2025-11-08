@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Beneficjenci</h1>
        <a href="{{ route('admin.beneficiaries.create') }}" class="btn btn-primary mb-3">Dodaj nowego</a>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}

                @if(session('user_email') && session('user_password'))
                    <hr>
                    <div class="mb-2">
                        <strong>Email (login):</strong> <span id="newUserEmail">{{ session('user_email') }}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('newUserEmail')">Kopiuj</button>
                    </div>
                    <div>
                        <strong>Hasło:</strong> <span id="newUserPassword">{{ session('user_password') }}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('newUserPassword')">Kopiuj</button>
                    </div>
                @endif

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Email</th>
                <th>Aktywny</th>
                <th>Link</th>
                <th>Slug</th>
                <th>Licencje</th>
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
                    <td>{{ $b->slug }}</td>
                    <td>
                        @foreach($b->licenses as $license)
                            <span class="badge bg-info">{{ $license->type }}: {{ $license->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.beneficiaries.edit', $b) }}" class="btn btn-sm btn-warning">Edytuj</a>
                        <form action="{{ route('admin.beneficiaries.destroy', $b) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Na pewno chcesz usunąć?')">Usuń</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Skopiowano do schowka!');
            });
        }
    </script>
@endsection
