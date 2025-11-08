@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edytuj beneficjenta</h1>

        <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary mb-3">Powrót do listy</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="beneficiary-form" action="{{ route('admin.beneficiaries.update', $beneficiary) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="first_name" class="form-label">Imię</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                       value="{{ old('first_name', $beneficiary->first_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Nazwisko</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                       value="{{ old('last_name', $beneficiary->last_name) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="{{ old('email', $beneficiary->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Telefon</label>
                <input type="text" class="form-control" id="phone" name="phone"
                       value="{{ old('phone', $beneficiary->phone) }}">
            </div>

            <div class="mb-3">
                <label for="class_link" class="form-label">Link do zajęć</label>
                <input type="url" class="form-control" id="class_link" name="class_link"
                       value="{{ old('class_link', $beneficiary->class_link) }}">
            </div>


            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="active" name="active" {{ $beneficiary->active ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktywny</label>
            </div>

            <h4>Licencje</h4>
            <table class="table table-bordered" id="licenses-table">
                <thead>
                <tr>
                    <th>Typ</th>
                    <th>Nazwa</th>
                    <th>Akcja</th>
                </tr>
                </thead>
                <tbody>
                @foreach($beneficiary->licenses as $license)
                    <tr data-id="{{ $license->id }}">
                        <td><input type="text" name="licenses[{{ $license->id }}][type]" value="{{ $license->type }}" class="form-control" required></td>
                        <td><input type="text" name="licenses[{{ $license->id }}][name]" value="{{ $license->name }}" class="form-control" required></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-license">Usuń</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="button" id="add-license" class="btn btn-success mb-3">Dodaj licencję</button>

            <button type="submit" class="btn btn-primary">Zapisz</button>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let licenseIndex = 0;

                // Dodawanie nowej licencji
                document.getElementById('add-license').addEventListener('click', function () {
                    licenseIndex++;
                    const tbody = document.querySelector('#licenses-table tbody');
                    const row = document.createElement('tr');
                    row.innerHTML = `
            <td><input type="text" name="licenses[new_${licenseIndex}][type]" class="form-control" required></td>
            <td><input type="text" name="licenses[new_${licenseIndex}][name]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger btn-sm delete-license">Usuń</button></td>
        `;
                    tbody.appendChild(row);
                });

                // Usuwanie licencji
                document.querySelector('#licenses-table').addEventListener('click', function(e){
                    if(e.target.classList.contains('delete-license')){
                        const row = e.target.closest('tr');
                        row.remove();
                    }
                });

                // Opcjonalnie: obsługa AJAX dla natychmiastowego zapisu
                /*
                document.getElementById('beneficiary-form').addEventListener('submit', function(e){
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert('Zapisano!');
        })
        .catch(err => console.error(err));
    });
    */
            });
        </script>
    @endpush
@endsection
