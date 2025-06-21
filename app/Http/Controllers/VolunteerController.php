<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class VolunteerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index(): View
    {
        $user = Auth::user();
        $existingRequest = null;

        if ($user) {
            $existingRequest = Volunteer::where('user_id', $user->id)->first();
        }

        return view('volunteers.index', compact('existingRequest'));
    }

    public function show(): View
    {
        // ① Renombra $volunteers a $users
        $users = User::role(['Visitor','Volunteer junior','Volunteer senior'])
            ->with('volunteer')
            ->orderBy('name')
            ->paginate(20);

        // ② Compacta 'users' (igual al nombre de la variable)
        return view('volunteers.show', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cv' => ['required', 'file', 'mimes:pdf'],
            'info_adicional' => ['string', 'max:255'],
        ]);

        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
            $filename = $request->file('cv')->getClientOriginalName();

            $volunteer = Volunteer::create([
                'user_id' => auth()->user()->id,
                'cv_filename' => $filename,
                'cv_path' => $path,
                'additional_info' => $request->info_adicional,
                'requested_date' => now(),
            ]);

            if(!$volunteer){
                return redirect()->route('volunteers.index')->with('error', 'No se pudo procesar su solicitud. Por favor, intente de nuevo.');
            }

            return redirect()->route('volunteers.index')->with('success', 'Solicitud procesada con éxito. Nos pondemos en contacto contigo pronto.');

        } else {
            return redirect()->route('volunteers.index')->with('error', 'Debe proporcionar un CV en PDF.');
        }
    }

    public function approve(Volunteer $volunteer, Request $request)
    {
        $volunteer->update([
            'status' => 'active',
            'approved_date' => now(),
        ]);

        $user = $volunteer->user;
        $user->assignRole($request->type);

        // if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Voluntario aprobado con éxito']);
        // }

        // return redirect()->route('volunteers.show')->with('success', 'Voluntario aprobado con éxito.');
    }



    public function decline(Volunteer $volunteer)
    {
        $volunteer->update([
            'status' => 'inactive',
            'inactive_date' => now(),
        ]);

        $user = $volunteer->user;

        if ($user->hasRole('Volunteer junior')) {
            $user->removeRole('Volunteer junior');
        }
        if ($user->hasRole('Volunteer senior')) {
            $user->removeRole('Volunteer senior');
        }

        // Comprobar si la solicitud es de tipo AJAX
        // if (request()->wantsJson()) {
        return response()->json(['message' => 'Voluntario rechazado con éxito']);
        // }

        // return redirect()->route('volunteers.show')->with('success', 'Voluntario rechazado con éxito.');
    }
}