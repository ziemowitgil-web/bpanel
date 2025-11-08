<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Support\Str;

class BeneficiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // tylko logowanie wymagane
    }

    // Lista beneficjentów
    public function index()
    {
        $beneficiaries = Beneficiary::with('user', 'licenses')->get();
        return view('admin.beneficiaries.index', compact('beneficiaries'));
    }

    // Formularz tworzenia
    public function create()
    {
        return view('admin.beneficiaries.create');
    }

    // Zapis nowego beneficjenta
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:beneficiaries,email',
            'phone'      => 'nullable|string|max:20',
            'class_link' => 'nullable|url|max:255',
            'active'     => 'sometimes|boolean',
        ]);

        $slug = $this->generateSlug($request->first_name, $request->last_name);

        $beneficiary = Beneficiary::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'class_link' => $request->class_link,
            'active'     => $request->has('active'),
            'slug'       => $slug,
        ]);

        // Tworzenie użytkownika (login = email)
        $plainPassword = Str::random(8);
        $user = User::create([
            'name'     => $beneficiary->first_name . ' ' . $beneficiary->last_name,
            'email'    => $beneficiary->email,
            'password' => bcrypt($plainPassword),
        ]);
        $beneficiary->user()->save($user);

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent utworzony!')
            ->with('user_email', $user->email)
            ->with('user_password', $plainPassword);
    }

    // Formularz edycji
    public function edit(Beneficiary $beneficiary)
    {
        $beneficiary->load('licenses', 'user');
        return view('admin.beneficiaries.edit', compact('beneficiary'));
    }

    // Aktualizacja beneficjenta wraz z licencjami
    public function update(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:beneficiaries,email,' . $beneficiary->id,
            'phone'           => 'nullable|string|max:20',
            'class_link'      => 'nullable|url|max:255',
            'active'          => 'sometimes|boolean',
            'licenses.*.type' => 'required|string|max:255',
            'licenses.*.name' => 'required|string|max:255',
        ]);

        // Generowanie nowego sluga tylko jeśli zmieniło się imię lub nazwisko
        if ($beneficiary->first_name !== $request->first_name || $beneficiary->last_name !== $request->last_name) {
            $beneficiary->slug = $this->generateSlug($request->first_name, $request->last_name);
        }

        $beneficiary->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'class_link' => $request->class_link,
            'active'     => $request->has('active'),
        ]);

        // Obsługa licencji
        if ($request->has('licenses')) {
            foreach ($request->licenses as $id => $data) {
                if (is_numeric($id)) {
                    $license = $beneficiary->licenses()->find($id);
                    if ($license) $license->update($data);
                } else {
                    $beneficiary->licenses()->create($data);
                }
            }
        }

        // Jeśli użytkownik istnieje, zaktualizuj hasło
        $plainPassword = null;
        if ($beneficiary->user) {
            $plainPassword = Str::random(8);
            $beneficiary->user->update([
                'name'     => $beneficiary->first_name . ' ' . $beneficiary->last_name,
                'email'    => $beneficiary->email,
                'password' => bcrypt($plainPassword),
            ]);
        }

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent zaktualizowany wraz z licencjami!')
            ->with('user_email', $beneficiary->user?->email)
            ->with('user_password', $plainPassword);
    }

    // Usuwanie beneficjenta
    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->licenses()->delete();
        if ($beneficiary->user) $beneficiary->user->delete();
        $beneficiary->delete();

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent usunięty!');
    }

    // Generowanie unikalnego sluga
    protected function generateSlug($firstName, $lastName)
    {
        $slug = Str::lower(Str::ascii(substr($firstName, 0, 1) . $lastName));

        // Jeśli istnieje konflikt, używamy pierwsze 3 litery imienia + nazwisko
        if (Beneficiary::where('slug', $slug)->exists()) {
            $slug = Str::lower(Str::ascii(substr($firstName, 0, 3) . $lastName));
            $originalSlug = $slug;
            $counter = 1;

            while (Beneficiary::where('slug', $slug)->exists()) {
                $slug = $originalSlug . $counter;
                $counter++;
            }
        }

        return $slug;
    }
}
