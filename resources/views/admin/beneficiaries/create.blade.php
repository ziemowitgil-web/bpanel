@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Dodaj nowego beneficjenta</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.beneficiaries.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Imię</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link do zajęć</label>
                    <input type="url" name="class_link" class="form-control" value="{{ old('class_link') }}">
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="active" class="form-check-input" @if(old('active')) checked @endif>
                <label class="form-check-label">Aktywny</label>
            </div>

            <hr>
            <h4>Licencje</h4>
            <div id="licenses-container"></div>
            <button type="button" id="add-license" class="btn btn-sm btn-success mb-3">Dodaj licencję</button>

            <div>
                <button type="submit" class="btn btn-primary">Zapisz</button>
                <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary">Powrót</a>
            </div>
        </form>
    </div>

    <script>
        let licenseIndex = 0;
        document.getElementById('add-license').addEventListener('click', function() {
            const container = document.getElementById('licenses-container');
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
        <div class="col-md-4"><input type="text" name="licenses[${licenseIndex}][type]" class="form-control" placeholder="Typ licencji"></div>
        <div class="col-md-6"><input type="text" name="licenses[${licenseIndex}][name]" class="form-control" placeholder="Nazwa licencji"></div>
        <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-license">Usuń</button></div>
    `;
            container.appendChild(div);
            licenseIndex++;
        });

        document.getElementById('licenses-container').addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-license')) {
                e.target.closest('.row').remove();
            }
        });
    </script>
@endsection
