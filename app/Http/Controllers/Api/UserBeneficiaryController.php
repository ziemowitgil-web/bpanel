<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Beneficiary;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserBeneficiaryController extends Controller
{
    /**
     * Tworzy nowego użytkownika i beneficjenta.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // User
            'user.name' => 'required|string|unique:users,name',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|string|min:6',

            // Beneficjent
            'beneficiary.first_name' => 'required|string',
            'beneficiary.last_name' => 'required|string',
            'beneficiary.email' => 'required|email|unique:beneficiaries,email',
            'beneficiary.phone' => 'nullable|string',
            'beneficiary.class_link' => 'nullable|url',
            'beneficiary.instructor_id' => 'nullable|exists:instructors,id',
            'beneficiary.active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Tworzymy User
        $userData = $request->input('user');
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);

        // Tworzymy Beneficiary
        $beneficiaryData = $request->input('beneficiary');
        $beneficiaryData['slug'] = $this->generateSlug($beneficiaryData['first_name'], $beneficiaryData['last_name']);
        $beneficiary = Beneficiary::create($beneficiaryData);

        // Powiązanie
        $beneficiary->user()->save($user);

        return response()->json([
            'message' => 'Użytkownik i beneficjent utworzeni pomyślnie',
            'user' => $user,
            'beneficiary' => $beneficiary,
        ], 201);
    }

    /**
     * Generowanie unikalnego sluga dla beneficjenta
     */
    protected function generateSlug($firstName, $lastName)
    {
        $slug = Str::lower(Str::ascii(substr($firstName, 0, 1) . substr($lastName, 0, 1)));
        $originalSlug = $slug;
        $counter = 1;

        while (Beneficiary::where('slug', $slug)->exists()) {
            $slug = $originalSlug . $counter;
            $counter++;
        }

        return $slug;
    }
}
