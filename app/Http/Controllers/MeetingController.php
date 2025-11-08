<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiary;

class MeetingController extends Controller
{
    /**
     * Redirect to the class link based on slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function redirectToClass($slug)
    {
        $beneficiary = Beneficiary::where('slug', $slug)->first();

        if (!$beneficiary) {
            abort(404, 'Beneficjent nie znaleziony.');
        }

        if (!$beneficiary->class_link) {
            abort(404, 'Link do zajęć nie istnieje.');
        }

        return redirect()->away($beneficiary->class_link);
    }
}
