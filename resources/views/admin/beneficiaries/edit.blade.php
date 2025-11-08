@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Edytuj beneficjenta: {{ $beneficiary->first_name }} {{ $beneficiary->last_name }}</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.beneficiaries.update', $beneficiary) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="first_name" class="form-label">Imię</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $beneficiary->first_name) }}" class="form-control @error('first_name') is-invalid @enderror">
                    @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="last_name" class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $beneficiary->last_name) }}" class="form-control @error('last_name') is-invalid @enderror">
                    @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $beneficiary->email) }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="phone" class="form-label">Telefon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $beneficiary->phone) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="class_link" class="form-label">Link do zajęć</label>
                    <input type="url" name="class_link" id="class_link" value="{{ old('class_link', $beneficiary->class_link) }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label for="instructor_id" class="form-label">Instruktor</label>
                    <select name="instructor_id" id="instructor_id" class="form-select">
                        <option value="">-- brak --</option>
                        @foreach($instructors as $inst)
                            <option value="{{ $inst->id }}" @if(old('instructor_id', $beneficiary->instructor_id) == $inst->id) selected @endif>
                                {{ $inst->first_name }} {{ $inst->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="active" id="active" class="form-check-input" @if(old('active', $beneficiary->active)) checked @endif>
                <label for="active" class="form-check-label">Aktywny</label>
            </div>

            <hr>
            <h4>Licencje</h4>
            <div id="licenses-container">
                @foreach(old('licenses', $beneficiary->licenses->toArray()) as $index => $license)
                    <div class="row mb-2 license-item">
                        <input type="hidden" name="licenses[{{ $index }}][id]" value="{{ $license['id'] ?? '' }}">
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
            </div>
            <button type="button" id="add-license" class="btn btn-sm btn-success mb-3">Dodaj licencję</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Zapisz zmiany</button>
                <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Powrót</a>
            </div>
        </form>
    </div>

    {{-- Skrypty do dodawania/usuwania licencji --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let licenseIndex = {{ count(old('licenses', $beneficiary->licenses)) }};

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
