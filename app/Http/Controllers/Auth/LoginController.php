<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Ten kontroler obsługuje logowanie użytkowników do aplikacji
    | i przekierowanie ich po zalogowaniu.
    |
    */

    use AuthenticatesUsers;

    /**
     * Gdzie przekierować użytkownika po zalogowaniu.
     *
     * @var string
     */
    // Możesz ustawić domyślną ścieżkę, jeśli nie używasz metody redirectTo()
    protected $redirectTo = '/dashboard';

    /**
     * Utwórz nową instancję kontrolera.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Opcjonalnie: dynamiczne przekierowanie w zależności od roli.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        // Jeżeli użytkownik jest adminem
        if ($user && $user->is_admin) {
            return '/admin/dashboard';
        }

        // Standardowy panel dla beneficjenta
        return '/dashboard';
    }

    /**
     * Dodatkowa metoda po wylogowaniu (opcjonalnie)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/login')->with('success', 'Pomyślnie wylogowano.');
    }
}
