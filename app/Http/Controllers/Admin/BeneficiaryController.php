<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\User;
use App\Models\Instructor;
use Illuminate\Support\Str;
use App\Mail\BeneficiaryCredentials;
use Illuminate\Support\Facades\Mail;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        $beneficiaries = Beneficiary::with('user')->get(); // załaduj użytkownika
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

        // generowanie slug: pierwsze 3 litery imienia i nazwiska + 2 losowe cyfry
        $slug = strtolower(substr($data['first_name'], 0, 3) . substr($data['last_name'], 0, 3) . rand(10, 99));
        $data['slug'] = $slug;

        $beneficiary = Beneficiary::create($data);

        // Tworzenie użytkownika + losowe hasło
        $plainPassword = Str::random(12);
        $user = User::create([
            'name' => $beneficiary->first_name . ' ' . $beneficiary->last_name,
            'email' => $beneficiary->email,
            'password' => bcrypt($plainPassword),
        ]);

        $beneficiary->user()->save($user);

        // Wysyłka maila powitalnego
        Mail::to($user->email)
            ->cc('dev@ziemowit.me')
            ->send(new BeneficiaryCredentials($user, $plainPassword));

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent dodany!')
            ->with('user_email', $user->email)
            ->with('user_password', $plainPassword);
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
        if ($beneficiary->user) $beneficiary->user->delete();
        $beneficiary->delete();
        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent usunięty!');
    }

    // Wysyłka maila powitalnego ręcznie
    public function sendWelcomeMail(Beneficiary $beneficiary)
    {
        if (!$beneficiary->user) {
            return redirect()->back()->with('error', 'Beneficjent nie ma konta użytkownika.');
        }

        try {
            Mail::to($beneficiary->user->email)
                ->cc('dev@ziemowit.me')
                ->send(new BeneficiaryCredentials($beneficiary->user, '---')); // jeśli nie zmieniasz hasła, możesz przekazać placeholder

            return redirect()->back()->with('success', 'Mail powitalny wysłany.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Błąd wysyłki: ' . $e->getMessage());
        }
    }
}
