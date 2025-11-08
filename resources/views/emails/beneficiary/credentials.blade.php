@component('mail::message')
    # Twoje dane logowania

    Witaj {{ $user->name }},

    Twoje konto w panelu eduk@acja zostało utworzone.

    **Login (email):** {{ $user->email }}
    **Hasło:** {{ $password }}

    @component('mail::button', ['url' => route('login')])
        Zaloguj się do panelu
    @endcomponent

    <br>
    **FEER - Fundacja Edukacji Empatii i Rozwoju "FEER"**
@endcomponent
