@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Edytuj beneficjenta</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
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

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Imię</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $beneficiary->first_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $beneficiary->last_name) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $beneficiary->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $beneficiary->phone) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Link do zajęć</label>
                <input type="url" name="class_link" class="form-control" value="{{ old('class_link', $beneficiary->class_link) }}">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="active" id="active" {{ old('active', $beneficiary->active) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktywny</label>
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-warning" onclick="generateSlug()">Generuj nowy slug</button>
                <input type="text" id="slug" name="slug" class="form-control mt-2" value="{{ $beneficiary->slug }}" readonly>
            </div>

            <h5>Licencje</h5>
            <div id="licenses-container" class="mb-3">
                @foreach(old('licenses', $beneficiary->licenses->toArray()) as $i => $license)
                    <div class="license row mb-2">
                        <div class="col-md-5">
                            <input type="text" name="licenses[{{ $i }}][type]" class="form-control" placeholder="Typ" value="{{ $license['type'] }}" required>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="licenses[{{ $i }}][name]" class="form-control" placeholder="Nazwa" value="{{ $license['name'] }}" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100" onclick="removeLicense(this)">Usuń</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addLicense()">Dodaj licencję</button>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary">Anuluj</a>
            </div>
        </form>

        @if($beneficiary->user)
            <hr>
            <h5>Konto użytkownika</h5>
            <p>Email: {{ $beneficiary->user->email }}</p>
            <form action="{{ route('admin.beneficiaries.deleteUser', $beneficiary) }}" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć konto użytkownika?');">
                @csrf
                <button type="submit" class="btn btn-danger">Usuń konto użytkownika</button>
            </form>
        @else
            <hr>
            <form action="{{ route('admin.beneficiaries.sendWelcomeMail', $beneficiary) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Wyślij mail powitalny i utwórz konto</button>
            </form>
        @endif
    </div>

    <script>
        function addLicense() {
            const container = document.getElementById('licenses-container');
            const index = container.children.length;
            const div = document.createElement('div');
            div.classList.add('license', 'row', 'mb-2');
            div.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="licenses[${index}][type]" class="form-control" placeholder="Typ" required>
        </div>
        <div class="col-md-5">
            <input type="text" name="licenses[${index}][name]" class="form-control" placeholder="Nazwa" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100" onclick="removeLicense(this)">Usuń</button>
        </div>
    `;
            container.appendChild(div);
        }

        function removeLicense(button) {
            button.closest('.license').remove();
        }

        // Funkcja generująca slug z polskich liter na zwykłe
        function generateSlug() {
            const first = document.querySelector('input[name="first_name"]').value;
            const last = document.querySelector('input[name="last_name"]').value;

            let slug = (first + last).toLowerCase();
            slug = slug.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); // usuwa polskie znaki
            slug = slug.replace(/[^a-z0-9]/g, ''); // usuwa inne znaki
            slug += Math.floor(10 + Math.random() * 90); // dodaje 2 losowe cyfry

            document.getElementById('slug').value = slug;
        }
    </script>
@endsection
