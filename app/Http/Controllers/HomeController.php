<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:edit-task', ['only' => ['edit','complete']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $user = Auth::user();

        // Recupera todas las tareas asignadas al usuario actual que no han sido canceladas.
        $tasks = $user->tasks()->wherePivot('status', '!=', 'cancelled')->get();

        return view('home', compact('tasks'));
    }

}
