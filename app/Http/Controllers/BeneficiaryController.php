<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\User;
use App\Models\Instructor;
use Illuminate\Support\Str;

class BeneficiaryController extends Controller
{
    public function __construct()
    {
        // Możesz tu dodać middleware dla admina
        $this->middleware('auth');
        $this->middleware('admin'); // przykładowo, jeśli masz flagę is_admin
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
        $instructors = Instructor::all() ?? collect();
        return view('admin.beneficiaries.create', compact('instructors'));
    }

    // Zapis nowego beneficjenta
    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:beneficiaries,email',
            'phone'         => 'nullable|string|max:20',
            'class_link'    => 'nullable|url|max:255',
            'instructor_id' => 'nullable|exists:instructors,id',
            'active'        => 'nullable|boolean',
        ]);

        $slug = $this->generateSlug($request->first_name, $request->last_name);

        $beneficiary = Beneficiary::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'class_link'    => $request->class_link,
            'active'        => $request->has('active'),
            'slug'          => $slug,
            'instructor_id' => $request->instructor_id,
        ]);

        // Tworzenie użytkownika
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
        $instructors = Instructor::all() ?? collect();
        $beneficiary->load('licenses', 'user');
        return view('admin.beneficiaries.edit', compact('beneficiary', 'instructors'));
    }

    // Aktualizacja beneficjenta wraz z licencjami
    public function update(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:beneficiaries,email,' . $beneficiary->id,
            'phone'          => 'nullable|string|max:20',
            'class_link'     => 'nullable|url|max:255',
            'instructor_id'  => 'nullable|exists:instructors,id',
            'active'         => 'nullable|boolean',
            'licenses.*.type'=> 'required|string|max:255',
            'licenses.*.name'=> 'required|string|max:255',
        ]);

        if ($beneficiary->first_name !== $request->first_name || $beneficiary->last_name !== $request->last_name) {
            $beneficiary->slug = $this->generateSlug($request->first_name, $request->last_name);
        }

        $beneficiary->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'class_link'    => $request->class_link,
            'active'        => $request->has('active'),
            'instructor_id' => $request->instructor_id,
        ]);

        // Aktualizacja licencji
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

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent zaktualizowany wraz z licencjami!');
    }

    // Usuwanie beneficjenta
    public function destroy(Beneficiary $beneficiary)
    {
        // Usuń licencje
        $beneficiary->licenses()->delete();

        // Usuń użytkownika powiązanego
        if ($beneficiary->user) {
            $beneficiary->user->delete();
        }

        // Usuń beneficjenta
        $beneficiary->delete();

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent usunięty!');
    }

    // Generowanie unikalnego sluga
    protected function generateSlug($firstName, $lastName)
    {
        // Domyślny slug: pierwsza litera imienia + pierwsza litera nazwiska
        $slug = Str::lower(Str::ascii(substr($firstName, 0, 1) . substr($lastName, 0, 1)));

        if (Beneficiary::where('slug', $slug)->exists()) {
            // Konflikt: używamy 3 liter imienia + 3 liter nazwiska
            $slug = Str::lower(Str::ascii(substr($firstName, 0, 3) . substr($lastName, 0, 3)));
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
