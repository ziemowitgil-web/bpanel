@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Edytuj beneficjenta</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.beneficiaries.update', $beneficiary) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Imię</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $beneficiary->first_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nazwisko</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $beneficiary->last_name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $beneficiary->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefon</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $beneficiary->phone) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Link do zajęć</label>
                <input type="url" name="class_link" class="form-control" value="{{ old('class_link', $beneficiary->class_link) }}">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="active" id="active" {{ old('active', $beneficiary->active) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktywny</label>
            </div>

            <h5>Licencje</h5>
            <div id="licenses-container">
                @foreach(old('licenses', $beneficiary->licenses->toArray()) as $i => $license)
                    <div class="license mb-2">
                        <input type="text" name="licenses[{{ $i }}][type]" placeholder="Typ" value="{{ $license['type'] }}" required>
                        <input type="text" name="licenses[{{ $i }}][name]" placeholder="Nazwa" value="{{ $license['name'] }}" required>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addLicense()">Dodaj licencję</button>

            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>

    <script>
        function addLicense() {
            const container = document.getElementById('licenses-container');
            const index = container.children.length;
            const div = document.createElement('div');
            div.classList.add('license', 'mb-2');
            div.innerHTML = `
        <input type="text" name="licenses[${index}][type]" placeholder="Typ" required>
        <input type="text" name="licenses[${index}][name]" placeholder="Nazwa" required>
    `;
            container.appendChild(div);
        }
    </script>
@endsection
