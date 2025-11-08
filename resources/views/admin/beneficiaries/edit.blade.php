@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Edytuj beneficjenta</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.beneficiaries.update', $beneficiary) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Imię</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $beneficiary->first_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $beneficiary->last_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $beneficiary->email) }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $beneficiary->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Link do zajęć</label>
                    <input type="url" name="class_link" class="form-control" value="{{ old('class_link', $beneficiary->class_link) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Instruktor</label>
                    <select name="instructor_id" class="form-select">
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
                <input type="checkbox" name="active" class="form-check-input" @if(old('active', $beneficiary->active)) checked @endif>
                <label class="form-check-label">Aktywny</label>
            </div>

            <hr>
            <h4>Licencje</h4>
            <div id="licenses-container">
                @foreach(old('licenses', $beneficiary->licenses->toArray()) as $index => $license)
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <input type="text" name="licenses[{{ $index }}][type]" class="form-control" value="{{ $license['type'] }}">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="licenses[{{ $index }}][name]" class="form-control" value="{{ $license['name'] }}">
                        </div>
                        <div class="col-md-2">
