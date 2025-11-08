<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\BeneficiaryCredentials;
use Illuminate\Support\Facades\Mail;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        $beneficiaries = Beneficiary::with('user')->get();
        return view('admin.beneficiaries.index', compact('beneficiaries','instructors'));
    }

    public function create()
    {
        return view('admin.beneficiaries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:beneficiaries,email',
            'class_link' => 'nullable|url',
            'active' => 'boolean',
        ]);

        // Generowanie slug: pierwsze 3 litery imienia i nazwiska + 2 losowe cyfry
        $slug = strtolower(substr($data['first_name'], 0, 3) . substr($data['last_name'], 0, 3) . rand(10, 99));
        $data['slug'] = $slug;

        Beneficiary::create($data);

        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent dodany!');
    }

    public function edit(Beneficiary $beneficiary)
    {
        return view('admin.beneficiaries.edit', compact('beneficiary'));
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:beneficiaries,email,' . $beneficiary->id,
            'class_link' => 'nullable|url',
            'active' => 'boolean',
        ]);

        $beneficiary->update($data);

        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent zaktualizowany!');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        // Usuń powiązane konto użytkownika, jeśli istnieje
        if ($beneficiary->user) {
            $beneficiary->user->delete();
        }

        $beneficiary->delete();

        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent usunięty!');
    }

    /**
     * Wyślij mail powitalny do beneficjenta.
     * Jeśli nie ma konta, tworzy konto i generuje hasło.
     */
    public function sendWelcomeMail(Beneficiary $beneficiary)
    {
        // Jeśli Beneficjent nie ma konta, tworzymy użytkownika
        if (!$beneficiary->user) {
            $plainPassword = Str::random(16);

            $user = $beneficiary->user()->create([
                'name' => $beneficiary->first_name . ' ' . $beneficiary->last_name,
                'email' => $beneficiary->email,
                'password' => bcrypt($plainPassword),
            ]);

            // Wysyłka maila powitalnego
            Mail::to($user->email)
                ->send(new BeneficiaryCredentials($user, $plainPassword));

            return redirect()->route('admin.beneficiaries.index')
                ->with('success', 'Mail powitalny wysłany! Konto utworzone.')
                ->with('user_email', $user->email)
                ->with('user_password', $plainPassword);
        }

        return redirect()->route('admin.beneficiaries.index')
            ->with('info', 'Beneficjent już ma konto. Możesz wysłać mail ponownie z edycji użytkownika.');
    }

    /**
     * Usuń konto użytkownika powiązanego z beneficjentem, bez usuwania beneficjenta.
     */
    public function deleteUser(Beneficiary $beneficiary)
    {
        if ($beneficiary->user) {
            $email = $beneficiary->user->email;
            $beneficiary->user->delete();

            return redirect()->route('admin.beneficiaries.index')
                ->with('success', "Konto użytkownika ({$email}) zostało usunięte.");
        }

        return redirect()->route('admin.beneficiaries.index')
            ->with('error', 'Ten beneficjent nie ma konta użytkownika.');
    }
}
