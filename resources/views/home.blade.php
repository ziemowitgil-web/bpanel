@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Dashboard Beneficjenta</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Twoje dane
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Imię:</div>
                    <div class="col-md-8">{{ $beneficiary->first_name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Nazwisko:</div>
                    <div class="col-md-8">{{ $beneficiary->last_name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Email:</div>
                    <div class="col-md-8">{{ $beneficiary->email }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4 fw-bold">Telefon:</div>
                    <div class="col-md-8">{{ $beneficiary->phone }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Status:</div>
                    <div class="col-md-8">
                        @if($beneficiary->active)
                            <span class="badge bg-success">Aktywny</span>
                        @else
                            <span class="badge bg-secondary">Nieaktywny</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12 text-center">
                        @if($beneficiary->class_link)
                            <a href="{{ $beneficiary->class_link }}" target="_blank" class="btn btn-lg btn-success">
                                Dołącz do zajęć
                            </a>
                        @else
                            <span class="text-muted">Brak linku do zajęć</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
