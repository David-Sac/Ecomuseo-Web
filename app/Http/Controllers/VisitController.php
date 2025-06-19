<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\TourSchedule;
use App\Models\User;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;  // al inicio del fichero

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:edit-visit|delete-visit', ['only' => ['index', 'show']]);
        $this->middleware('permission:edit-visit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-visit', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visits = Visit::with(['user', 'tourSchedule.tour'])
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->orderBy('requested_date', 'asc')
            ->latest()
            ->paginate(10);

        return view('visits.index', compact('visits'));
    }


    private function validateVisit($tourSchedule, $numberOfPeople, $visitId = null)
    {
        $reservedSeats = $tourSchedule->visits()
            ->where('id', '!=', $visitId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('number_of_people');
        $availableSeats = $tourSchedule->max_capacity - $reservedSeats;

        if ($numberOfPeople > $availableSeats) {
            throw ValidationException::withMessages(['number_of_people' => 'El número de personas excede el cupo disponible para este horario del tour.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitRequest $request)
    {
        try {

            $validated = $request->validated();
            $tourSchedule = TourSchedule::findOrFail($validated['tour_schedule_id']);

            // Validar la capacidad disponible
            $this->validateVisit($tourSchedule, $validated['number_of_people']);

            $visit = new Visit();
            $visit->user_id = auth()->id();
            $visit->tour_schedule_id = $validated['tour_schedule_id'];
            $visit->number_of_people = $validated['number_of_people'];
            $visit->additional_info = $validated['additional_info'] ?? '';
            $visit->status = 'pending';
            $visit->requested_date = now();
            $visit->save();

            \Log::info('Reserva guardada con éxito.');

            return redirect()->route('tours.publicShow')->with('success', 'Reserva realizada con éxito. Espera la confirmación.');
        } catch (\Exception $e) {
            return redirect()->route('tours.publicShow')->with('error', 'Error al realizar la reserva: ' . $e->getMessage());
        }
    }


    public function approve($id)
    {
        $visit = Visit::findOrFail($id);
        $tourSchedule = $visit->tourSchedule;

        $this->validateVisit($tourSchedule, $visit->number_of_people, $visit->id);

        $visit->update(['status' => 'approved', 'approved_date' => now()]);
        return back()->with('success', 'Visita aprobada con éxito.');
    }

    public function decline($id)
    {
        $visit = Visit::findOrFail($id);
        $visit->update(['status' => 'rejected']);
        return back()->with('success', 'Visita rechazada con éxito.');
    }

    public function show($id): View
    {
        $visit = Visit::with(['user', 'tourSchedule.tour'])->findOrFail($id);
        return view('visits.show', compact('visit'));
    }


    public function publicVisits()
    {
        $userId = auth()->id();
        $visits = Visit::with('tourSchedule.tour')
            ->where('user_id', $userId)
            ->whereDate('requested_date', '>=', now()->toDateString())
            ->orderBy('requested_date', 'asc')
            ->get();

        return view('visits.publicVisits', compact('visits'));
    }


    public function edit(Visit $visit)
    {
        $visit->load('user', 'tourSchedule.tour');
        $users = User::all();
        $tourSchedules = TourSchedule::all();

        return view('visits.edit', compact('visit', 'users', 'tourSchedules'));
    }

    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        $validatedData = $request->validated();

        $visit->update($validatedData);

        return redirect()->route('visits.index')->with('success', 'Visita actualizada con éxito!');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        return redirect()->route('visits.index')
            ->withSuccess('Visita eliminada con éxito.');
    }
/**
 * Comprueba si el usuario ya solicitó este tour en cualquier horario.
 */
    public function checkDuplicate(Request $request)
    {
        $userId = auth()->id();
        $tourId = $request->input('tour_id');

        // Obtenemos todos los horarios de ese tour
        $scheduleIds = TourSchedule::where('tour_id', $tourId)->pluck('id');

        $exists = Visit::where('user_id', $userId)
            ->whereIn('tour_schedule_id', $scheduleIds)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return response()->json(['exists' => $exists]);
    }

}
