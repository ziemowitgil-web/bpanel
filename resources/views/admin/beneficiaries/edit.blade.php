@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Edytuj beneficjenta</h1>

        {{-- Alerty --}}
        @if(session('success') || session('error') || session('info'))
            @php
                $alertType = session('success') ? 'success' : (session('info') ? 'info' : 'danger');
                $message = session('success') ?? session('info') ?? session('error');
            @endphp
            <div class="alert alert-{{ $alertType }} alert-dismissible fade show">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
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

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="beneficiaryTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="dane-tab" data-bs-toggle="tab" href="#dane" role="tab">Dane podstawowe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="licenses-tab" data-bs-toggle="tab" href="#licenses" role="tab">Licencje</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="konto-tab" data-bs-toggle="tab" href="#konto" role="tab">Konto w systemie</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.beneficiaries.update', $beneficiary) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="tab-content">

                        <!-- TAB 1: Dane podstawowe -->
                        <div class="tab-pane fade show active" id="dane" role="tabpanel">
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
                        </div>

                        <!-- TAB 2: Licencje -->
                        <div class="tab-pane fade" id="licenses" role="tabpanel">
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
                        </div>

                        <!-- TAB 3: Konto w systemie -->
                        <div class="tab-pane fade" id="konto" role="tabpanel">
                            @if($beneficiary->user)
                                <p><strong>Email konta:</strong> {{ $beneficiary->user->email }}</p>
                                <form action="{{ route('admin.beneficiaries.delete-user', $beneficiary) }}" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć konto użytkownika?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger mb-2">Usuń konto użytkownika</button>
                                </form>
                                <form action="{{ route('admin.beneficiaries.welcome-mail', $beneficiary) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">Wyślij ponownie mail powitalny</button>
                                </form>
                            @else
                                <form action="{{ route('admin.beneficiaries.welcome-mail', $beneficiary) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Utwórz konto i wyślij mail powitalny</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                        <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary">Anuluj</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal do wyświetlania danych logowania --}}
    @if(session('user_email') && session('user_password'))
        <div class="modal fade" id="credentialsModal" tabindex="-1" aria-labelledby="credentialsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dane logowania beneficjenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Email:</strong> {{ session('user_email') }}</p>
                        <p><strong>Hasło:</strong> {{ session('user_password') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
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

        function generateSlug() {
            const first = document.querySelector('input[name="first_name"]').value;
            const last = document.querySelector('input[name="last_name"]').value;

            // Funkcja mapująca polskie litery
            const mapPolish = { 'ą':'a','ć':'c','ę':'e','ł':'l','ń':'n','ó':'o','ś':'s','ź':'z','ż':'z',
                'Ą':'A','Ć':'C','Ę':'E','Ł':'L','Ń':'N','Ó':'O','Ś':'S','Ź':'Z','Ż':'Z' };
            function replacePolish(str) {
                return str.split('').map(c => mapPolish[c] || c).join('');
            }

            let slug = replacePolish(first.slice(0,3) + last.slice(0,3)).toLowerCase();
            slug += Math.floor(10 + Math.random() * 90); // dwie losowe cyfry

            document.getElementById('slug').value = slug;
        }

        @if(session('user_email') && session('user_password'))
        var credentialsModal = new bootstrap.Modal(document.getElementById('credentialsModal'));
        credentialsModal.show();
        @endif
    </script>
@endsection

