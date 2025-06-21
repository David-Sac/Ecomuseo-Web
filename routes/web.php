<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Components;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\CompleteProfileController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\VolunteerController;

Route::get('/', [WelcomeController::class, 'index']);

Route::get('/google-auth/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google-auth/callback', function () {
    $user_google = Socialite::driver('google')->user();

    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
    ]);

    if (User::count() == 1) {
        $user->assignRole('Admin');
    } else {
        $user->assignRole('Visitor');
    }

    Auth::login($user);

    // Redirigir a completar perfil si faltan datos
    if (is_null($user->dni) || is_null($user->phone) || is_null($user->birthdate)) {
        return redirect()->route('complete-profile.create');
    }

    if ($user->hasRole("Admin")) {
        return redirect('/dashboard');
    } else {
        $components = Components::all();
        return view('welcome', compact('components'));
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Rutas accesibles sin autenticación
Route::get('/tour', [TourController::class, 'publicShow'])->name('tours.publicShow');
Route::get('/components/public/{id}', [ComponentsController::class, 'publicShow'])->name('components.publicShow');
// Índice público de blogs
Route::get('/blog', [BlogController::class, 'publicIndex'])
     ->name('blogs.publicIndex');

// Detalle público de un blog
Route::get('/blog/{id}', [BlogController::class, 'publicShow'])
     ->name('blogs.publicShow');Route::resource('donations', DonationController::class)->only(['index', 'store']);

Route::middleware(['auth', 'profile.complete'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->middleware(['redirect.if.not.admin.or.volunteer'])->name('home');

    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'components' => ComponentsController::class,
        'tours' => TourController::class,
        'visits' => VisitController::class,
        'blogs' => BlogController::class,
        'tasks' => TaskController::class,
        'volunteers' => VolunteerController::class,
    ]);
    Route::resource('donations', DonationController::class)->except(['index', 'store']);

    Route::post('/visits/store', [VisitController::class, 'store'])->name('visits.store');
    Route::get('/my-visits', [VisitController::class, 'publicVisits'])->name('visits.publicVisits');
    Route::post('/visits/{visit}/approve', [VisitController::class, 'approve'])->name('visits.approve');
    Route::post('/visits/{visit}/decline', [VisitController::class, 'decline'])->name('visits.decline');
    Route::post('/blogs/{blog}/approve', [BlogController::class, 'approve'])->name('blogs.approve');
    Route::post('/blogs/{blog}/decline', [BlogController::class, 'decline'])->name('blogs.decline');
    Route::get('/donations/manage', [DonationController::class, 'show'])->name('donations.show');
    Route::post('/donations/{donation}/approve', [DonationController::class, 'approve'])->name('donations.approve');
    Route::post('/donations/{donation}/decline', [DonationController::class, 'decline'])->name('donations.decline');
    Route::post('/donations/export', [DonationController::class, 'export'])->name('donations.export');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::post('/tasks/{task}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
    Route::post('/tasks/{task}/decline', [TaskController::class, 'decline'])->name('tasks.decline');
    Route::get('/volunteers/manage', [VolunteerController::class, 'show'])->name('volunteers.show');
    Route::post('/volunteers/{volunteer}/approve', [VolunteerController::class, 'approve'])->name('volunteers.approve');
    Route::post('/volunteers/{volunteer}/decline', [VolunteerController::class, 'decline'])->name('volunteers.decline');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
     ->name('tasks.destroy');
});

Route::get('/complete-profile', [CompleteProfileController::class, 'create'])->name('complete-profile.create')->middleware('auth');
Route::post('/complete-profile', [CompleteProfileController::class, 'store'])->name('complete-profile.store')->middleware('auth');

require __DIR__ . '/auth.php';
Route::middleware('auth')->post('/visits/check-duplicate', [\App\Http\Controllers\VisitController::class, 'checkDuplicate'])
     ->name('visits.checkDuplicate');