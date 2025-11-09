<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Instructor;
use Illuminate\Support\Str;
use App\Mail\BeneficiaryCredentials;
use Illuminate\Support\Facades\Mail;

class BeneficiaryController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        $beneficiaries = Beneficiary::with('user')->get();
        return view('admin.beneficiaries.index', compact('beneficiaries', 'instructors'));
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

        // Bezpieczna konwersja do UTF-8
        $data['first_name'] = mb_convert_encoding($data['first_name'], 'UTF-8', 'UTF-8');
        $data['last_name'] = mb_convert_encoding($data['last_name'], 'UTF-8', 'UTF-8');

        // Generowanie slug
        $slug = strtolower(
            $this->slugify(
                substr($data['first_name'], 0, 1) . substr($data['last_name'], 0, 1)
            )
        );

        $data['slug'] = $slug;

        $beneficiary = Beneficiary::create($data);

        // Tworzenie konta użytkownika po dodaniu beneficjenta
        if (!$beneficiary->user) {
            $plainPassword = substr(str_replace(['-', '_'], '', Str::uuid()->toString()), 0, 16);

            $user = $beneficiary->user()->create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($plainPassword),
            ]);

            // Wysyłka maila powitalnego
            Mail::to($user->email)->send(new BeneficiaryCredentials($user, $plainPassword));

            // Przekazanie danych do popup w widoku edycji
            return redirect()->route('admin.beneficiaries.edit', $beneficiary)
                ->with('success', 'Beneficjent dodany! Konto utworzone.')
                ->with('user_email', $user->email)
                ->with('user_password', $plainPassword);
        }

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent dodany!');
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

        $data['first_name'] = mb_convert_encoding($data['first_name'], 'UTF-8', 'UTF-8');
        $data['last_name'] = mb_convert_encoding($data['last_name'], 'UTF-8', 'UTF-8');

        $beneficiary->update($data);

        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent zaktualizowany!');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        if ($beneficiary->user) {
            $beneficiary->user->delete();
        }

        $beneficiary->delete();

        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent usunięty!');
    }

    public function sendWelcomeMail(Beneficiary $beneficiary)
    {
        if (!$beneficiary->user) {
            $plainPassword = substr(str_replace(['-', '_'], '', Str::uuid()->toString()), 0, 16);

            $user = $beneficiary->user()->create([
                'name' => $beneficiary->first_name . ' ' . $beneficiary->last_name,
                'email' => $beneficiary->email,
                'password' => bcrypt($plainPassword),
            ]);

            Mail::to($user->email)->send(new BeneficiaryCredentials($user, $plainPassword));

            return redirect()->route('admin.beneficiaries.edit', $beneficiary)
                ->with('success', 'Mail powitalny wysłany! Konto utworzone.')
                ->with('user_email', $user->email)
                ->with('user_password', $plainPassword);
        }

        return redirect()->route('admin.beneficiaries.index')
            ->with('info', 'Beneficjent już ma konto. Możesz wysłać mail ponownie z edycji użytkownika.');
    }

    public function deleteUser(Beneficiary $beneficiary)
    {
        if ($beneficiary->user) {
            $email = $beneficiary->user->email;
            $beneficiary->user->delete();

            return redirect()->route('admin.beneficiaries.edit', $beneficiary)
                ->with('success', "Konto użytkownika ({$email}) zostało usunięte.");
        }

        return redirect()->route('admin.beneficiaries.edit', $beneficiary)
            ->with('error', 'Ten beneficjent nie ma konta użytkownika.');
    }

    /**
     * Zamienia polskie znaki na "normalne" w stringu, bezpieczne dla URL i slugów.
     */
    private function slugify(string $text): string
    {
        $polish = ['ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż'];
        $latin  = ['a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z'];

        $text = str_replace($polish, $latin, $text);
        $text = preg_replace('/[^A-Za-z0-9\-]/', '', $text); // tylko litery, cyfry i -
        return $text;
    }
}
