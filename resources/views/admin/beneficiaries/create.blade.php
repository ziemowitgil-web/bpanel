@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Dodaj beneficjenta</h1>

        @if(session('success'))
            <div class="alert alert-success">{!! session('success') !!}</div>
        @endif

        <form action="{{ route('admin.beneficiaries.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">Imię</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="last_name" class="form-label">Nazwisko</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Telefon</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                </div>

                <div class="col-md-12">
                    <label for="class_link" class="form-label">Link do zajęć</label>
                    <input type="url" name="class_link" id="class_link" class="form-control">
                </div>

                <
                </div>

                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="active" id="active" class="form-check-input" value="1">
                        <label for="active" class="form-check-label">Aktywny</label>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </div>
        </form>
    </div>
@endsection
