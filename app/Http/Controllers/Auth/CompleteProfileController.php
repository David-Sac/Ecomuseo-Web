<?php

// app/Http/Controllers/Auth/CompleteProfileController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompleteProfileController extends Controller
{
    public function create()
    {
        return view('auth.complete-profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => ['required', 'numeric', 'digits:8'],
            'phone' => ['required', 'regex:/^(9)[0-9]{8}$/'],
            'birthdate' => ['required', 'date'],
        ]);

        $user = Auth::user();
        $user->update([
            'dni' => $request->dni,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
        ]);

        return redirect('/home')->with('success', 'Perfil completado con éxito.');
    }
}

