@component('mail::message')
    # Witaj {{ $user->name }}!

    Twoje konto w panelu eduk@acja zostało aktywowane.

    Twój login: {{ $user->email }}

    @component('mail::button', ['url' => url('/')])
        Zaloguj się
    @endcomponent

    Zespół FEER
@endcomponent
