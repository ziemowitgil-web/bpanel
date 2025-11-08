<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Beneficiary;
use App\Models\Instructor;


class BeneficiaryController extends Controller
{
    public function index()
    {
        $instructors = Instructor::all();
        $beneficiaries = Beneficiary::all();
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
        $beneficiary->delete();
        return redirect()->route('admin.beneficiaries.index')->with('success', 'Beneficjent usunięty!');
    }
}
