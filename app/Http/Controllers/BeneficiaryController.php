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
    public function index()
    {
        $beneficiaries = Beneficiary::with('user', 'licenses')->get();
        return view('admin.beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        $instructors = Instructor::all() ?? collect();
        return view('admin.beneficiaries.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => 'required|email|unique:beneficiaries,email',
            'phone'         => 'nullable|string',
            'class_link'    => 'nullable|url',
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

        // Tworzenie użytkownika dla beneficjenta
        $loginBase = strtolower(substr($request->first_name, 0, 3) . substr($request->last_name, 0, 3));
        $login = $loginBase . $beneficiary->id;
        $counter = 1;
        $originalLogin = $login;

        while (User::where('name', $login)->exists()) {
            $login = $originalLogin . $counter;
            $counter++;
        }

        $plainPassword = Str::random(8);

        $user = User::create([
            'name'     => $login,
            'email'    => $beneficiary->email,
            'password' => bcrypt($plainPassword),
        ]);

        $beneficiary->user()->save($user);

        // Przekazanie loginu i hasła do widoku
        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent utworzony!')
            ->with('user_login', $login)
            ->with('user_password', $plainPassword);
    }

    public function edit(Beneficiary $beneficiary)
    {
        $instructors = Instructor::all() ?? collect();
        $beneficiary->load('licenses');
        return view('admin.beneficiaries.edit', compact('beneficiary', 'instructors'));
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|email|unique:beneficiaries,email,' . $beneficiary->id,
            'phone'          => 'nullable|string',
            'class_link'     => 'nullable|url',
            'instructor_id'  => 'nullable|exists:instructors,id',
            'active'         => 'nullable|boolean',
            'licenses.*.type'=> 'required|string',
            'licenses.*.name'=> 'required|string',
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

    public function destroy(Beneficiary $beneficiary)
    {
        if ($beneficiary->user) $beneficiary->user->delete();
        $beneficiary->licenses()->delete();
        $beneficiary->delete();

        return redirect()->route('admin.beneficiaries.index')
            ->with('success', 'Beneficjent usunięty!');
    }

    protected function generateSlug($firstName, $lastName)
    {
        $slug = Str::lower(Str::ascii($firstName[0] . $lastName[0]));
        $originalSlug = $slug;
        $counter = 1;

        while (Beneficiary::where('slug', $slug)->exists()) {
            $slug = $originalSlug . $counter;
            $counter++;
        }

        return $slug;
    }
}
