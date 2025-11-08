@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Dodaj nowego beneficjenta</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.beneficiaries.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Imię</label>
                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nazwisko</label>
                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefon</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Link do zajęć</label>
                <input type="url" name="class_link" class="form-control" value="{{ old('class_link') }}">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="active" id="active" {{ old('active') ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktywny</label>
            </div>

            <button type="submit" class="btn btn-primary">Zapisz</button>
            <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary">Anuluj</a>
        </form>
    </div>
@endsection
