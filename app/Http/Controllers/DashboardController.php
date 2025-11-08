<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Beneficiary;

class DashboardController extends Controller
{
    /**
     * Wyświetla dashboard zalogowanego użytkownika.
     */
    public function index()
    {
        $user = Auth::user();
        $beneficiary = null;
        $isAdmin = $user->is_admin ?? false;

        if ($isAdmin) {
            // Admin zawsze widzi "systemowego" beneficjenta
            $beneficiary = Beneficiary::where('email', 'system@feer.org.pl')->first();
        } else {
            // Zwykły beneficjent po emailu
            $beneficiary = Beneficiary::where('email', $user->email)->first();
        }

        return view('dashboard', compact('user', 'beneficiary', 'isAdmin'));
    }
}
