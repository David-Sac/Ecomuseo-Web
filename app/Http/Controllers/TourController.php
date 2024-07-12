<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tour;
use App\Models\Components;
use App\Http\Requests\StoreTourRequest;
use App\Http\Requests\UpdateTourRequest;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr; // Importa la clase Arr para trabajar con arreglos


class TourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('publicShow');
        // $this->middleware('auth')->except(['publicShow', 'publicIndex']);

        // $this->middleware('auth');
        $this->middleware('permission:create-tour|edit-tour|delete-tour', ['only' => ['index','show']]);
        $this->middleware('permission:create-tour', ['only' => ['create','store']]);
        $this->middleware('permission:edit-tour', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-tour', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Ordena los tours por 'start_date' desde el más reciente hasta el más lejano y luego pagina los resultados
        $tours = Tour::with(['components', 'volunteers'])->orderBy('start_date', 'desc')->paginate(10);

        // Pasar los tours a la vista
        return view('tours.index', compact('tours'));
    }




    /**
     * Show the form for creating a new resource.
     */

     public function create(): View
     {
         $components = Components::all();
         $volunteers = User::role('volunteer')->get();

         return view('tours.create', compact('components', 'volunteers'));
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTourRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $tour = Tour::create($validatedData);

        if (!empty($validatedData['components'])) {
            $tour->components()->sync($validatedData['components']);
        }

        if (!empty($validatedData['volunteer_id'])) {
            $tour->volunteers()->attach($validatedData['volunteer_id']);
        }

        return redirect()->route('tours.index')->with('success', 'Tour creado con éxito!');
    }




    /**
     * Display the specified resource.
     */
    public function show(Tour $tour)
    {
        return view('tours.show', [
            'tour' => $tour
        ]);
    }

    public function publicShow(): View
    {
        $today = Carbon::now()->toDateString();

        $tours = Tour::with(['components', 'volunteers'])
                ->get()
                ->filter(function ($tour) use ($today) {
                    // Calcular la fecha de inicio de visibilidad basada en el periodo de visibilidad
                    $visibility_start_date = $this->calculateVisibilityStartDate($tour->start_date, $tour->visibility_period);

                    // Filtrar tours que están dentro del periodo de visibilidad
                    return $today >= $visibility_start_date && $today <= $tour->start_date;
                });

        foreach ($tours as $tour) {
            if ($tour->components->isNotEmpty() && $tour->components->first()->rutaImagenComponente) {
                $randomComponentWithImage = $tour->components->whereNotNull('rutaImagenComponente')->random();
                $tour->randomImage = $randomComponentWithImage->rutaImagenComponente;
            } else {
                $tour->randomImage = null;
            }
        }

        return view('tour', compact('tours'));
    }

    private function calculateVisibilityStartDate($startDate, $visibilityPeriod)
    {
        $startDate = Carbon::parse($startDate); // Convertir $startDate en un objeto Carbon

        switch ($visibilityPeriod) {
            case '1 día':
                return $startDate->copy()->subDay();
            case '2 días':
                return $startDate->copy()->subDays(2);
            case '1 semana':
                return $startDate->copy()->subWeek();
            case '2 semanas':
                return $startDate->copy()->subWeeks(2);
            case '1 mes':
                return $startDate->copy()->subMonth();
            case '2 meses':
                return $startDate->copy()->subMonths(2);
            case '3 meses':
                return $startDate->copy()->subMonths(3);
            default:
                return $startDate; // Si no hay un periodo de visibilidad definido, usar la fecha de inicio del tour
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tour $tour): View
    {
        $components = Components::all();
        $volunteers = User::role('Volunteer')->get();
        $assignedVolunteer = $tour->volunteers->first();

        return view('tours.edit', compact('tour', 'components', 'volunteers', 'assignedVolunteer'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTourRequest $request, Tour $tour): RedirectResponse
    {
        $validatedData = $request->validated();

        $tour->update($validatedData);

        if (array_key_exists('components', $validatedData)) {
            $tour->components()->sync($validatedData['components']);
        }

        if (!empty($validatedData['volunteer_id'])) {
            $tour->volunteers()->sync([$validatedData['volunteer_id']]);
            $volunteer = User::find($validatedData['volunteer_id']);
            $tour->update(['contact_info' => $volunteer->phone]);
        }

        return redirect()->route('tours.index')->with('success', 'Tour actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();
        return redirect()->route('tours.index')
                ->withSuccess('Tours is deleted successfully.');
    }
}
