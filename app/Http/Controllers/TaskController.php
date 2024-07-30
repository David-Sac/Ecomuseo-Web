<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
//use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use App\Models\Task;
use App\Models\User;
use App\Models\Components;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        /* $this->middleware('permission:create-task|edit-task|delete-task', ['only' => ['index','show','download']]);
        $this->middleware('permission:create-task', ['only' => ['create','store']]);
        $this->middleware('permission:edit-task', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-task', ['only' => ['destroy']]); */
    }

    public function index(): View
    {
        $tasks = Task::where('status', '!=', 'inactive')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Para cada tarea, carga los detalles de los componentes
        foreach ($tasks as $task) {
            if (!empty($task->components)) {
                $task->componentDetails = Components::whereIn('id', $task->components)->get();
            } else {
                $task->componentDetails = collect(); // Colección vacía si no hay componentes
            }
        }

        return view('tasks.index', compact('tasks'));
    }




    public function create(): View
    {
        $components = Components::all();
        // Cargar todos los voluntarios con sus roles y permisos
        $volunteers = User::with(['roles.permissions'])->get();

        return view('tasks.create', compact('components', 'volunteers'));
    }


    public function show(Task $task): View
    {
        $task = Task::findOrFail($task->id);
        return view('tasks.show', compact('task'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:250',
            'content' => 'required|string|max:255',
            'type' => 'required|in:create-blog,create-tour,create-donation,create-component',
            'volunteer_id' => 'required|exists:users,id',
            'components' => 'nullable|array',
            'components.*' => 'exists:components,id',
        ]);

        $type = explode('-', $request->type)[1];

        $newTask = Task::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $type,
            'status' => 'active',
            'components' => $request->components ?? [],
        ]);

        $newTask->volunteers()->attach($request->input('volunteer_id'), ['assigned_date' => now()]);

        return redirect()->route('tasks.index')->withSuccess('Tarea creada con éxito.');
    }


    public function edit(Task $task): View
    {
        // Cargar todos los componentes disponibles
        $components = Components::all();

        // Cargar todos los voluntarios, suponiendo que estos son usuarios con un rol específico
        // Modifica esto si usas un nombre de rol diferente o si tienes una lógica diferente para seleccionar voluntarios
        $volunteers = User::whereHas('roles', function ($query) {
            $query->where('name', 'like', '%volunteer%'); // Ajusta según tus nombres de roles
        })->get();

        // Obtener el primer voluntario asignado a la tarea, si existe
        $volunteerId = $task->volunteers->isNotEmpty() ? $task->volunteers->first()->pivot->volunteer_id : null;

        // Cargar los IDs de los componentes actualmente asociados a la tarea
        $selectedComponents = $task->components ?? [];

        return view('tasks.edit', compact('task', 'volunteers', 'volunteerId', 'components', 'selectedComponents'));
    }


    public function update(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:250',
            'content' => 'required|string|max:255',
            'type' => 'required',
            'volunteer_id' => 'required|exists:users,id',
            'components' => 'nullable|array',
            'components.*' => 'exists:components,id',
        ]);

        // Extraer solo la parte relevante del tipo para guardar en la base de datos
        $type = explode('-', $data['type'])[1]; // Esto toma "blog", "tour", etc.

        $task->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => $type,
            'components' => $data['components'] ?? [],
        ]);

        $task->volunteers()->sync([$data['volunteer_id'] => ['assigned_date' => now()]]);

        return redirect()->route('tasks.index')->withSuccess('Tarea actualizada con éxito.');
    }

    public function export(Request $request): Response
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();

        $tasks = Task::where('status', '!=', 'inactive')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->get();

        // Para cada tarea, cargar los detalles de los componentes
        foreach ($tasks as $task) {
            if (!empty($task->components)) {
                $task->componentDetails = Components::whereIn('id', $task->components)->get();
            } else {
                $task->componentDetails = collect(); // Colección vacía si no hay componentes
            }
        }

        $data = ['tasks' => $tasks, 'startDate' => $startDate, 'endDate' => $endDate];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('tasks.export', $data);

        $pdf->render();
        $canvas = $pdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Página $pageNumber de $pageCount";
            $font = $fontMetrics->getFont('monospace');
            $pageWidth = $canvas->get_width();
            $pageHeight = $canvas->get_height();
            $size = 10;
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $canvas->text($pageWidth - $width - 20, $pageHeight - 20, $text, $font, $size);
        });

        return $pdf->stream('Reporte_tareas_' . $startDate . '_' . $endDate . '.pdf');
    }



    public function complete(Task $task): RedirectResponse
    {
        $task = Task::findOrFail($task->id);

        $assignedDate = $task->volunteers->first()->pivot->assigned_date;

        $volunteerId = $task->volunteers->first()->pivot->volunteer_id;

        $task->volunteers()->sync([
            $volunteerId => ['status' => 'completed', 'completed_date' => now(), 'assigned_date' => $assignedDate],
        ]);

        return back();
        //return redirect()->route('home')->withSuccess('success', 'Task completed successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->update(['status' => 'inactive']);
        $task->volunteers->first()->pivot->update(['status' => 'cancelled']);

        return redirect()->route('tasks.index')->withSuccess('Tarea cancelada.');
    }
}
