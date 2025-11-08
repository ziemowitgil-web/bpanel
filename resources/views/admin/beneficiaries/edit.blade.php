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
                    <label for="first_name" class="form-label">Imię</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror">
                    @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="last_name" class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror">
                    @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="phone" class="form-label">Telefon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="class_link" class="form-label">Link do zajęć</label>
                    <input type="url" name="class_link" id="class_link" value="{{ old('class_link') }}" class="form-control">
                </div>

                <div class="col-md-4">
                    
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="active" id="active" class="form-check-input" @if(old('active')) checked @endif>
                <label for="active" class="form-check-label">Aktywny</label>
            </div>

            <hr>
            <h4>Licencje</h4>
            <div id="licenses-container">
                @if(old('licenses'))
                    @foreach(old('licenses') as $index => $license)
                        <div class="row mb-2 license-item">
                            <div class="col-md-4">
                                <input type="text" name="licenses[{{ $index }}][type]" class="form-control" placeholder="Typ licencji" value="{{ $license['type'] }}">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="licenses[{{ $index }}][name]" class="form-control" placeholder="Nazwa licencji" value="{{ $license['name'] }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-license">Usuń</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" id="add-license" class="btn btn-sm btn-success mb-3">Dodaj licencję</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Zapisz</button>
                <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Powrót</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let licenseIndex = {{ old('licenses') ? count(old('licenses')) : 0 }};

            document.getElementById('add-license').addEventListener('click', function() {
                const container = document.getElementById('licenses-container');
                const div = document.createElement('div');
                div.classList.add('row', 'mb-2', 'license-item');
                div.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="licenses[${licenseIndex}][type]" class="form-control" placeholder="Typ licencji">
            </div>
            <div class="col-md-6">
                <input type="text" name="licenses[${licenseIndex}][name]" class="form-control" placeholder="Nazwa licencji">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-license">Usuń</button>
            </div>
        `;
                container.appendChild(div);
                licenseIndex++;
            });

            document.getElementById('licenses-container').addEventListener('click', function(e) {
                if(e.target.classList.contains('remove-license')) {
                    e.target.closest('.license-item').remove();
                }
            });
        });
    </script>
@endsection
