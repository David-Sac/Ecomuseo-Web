<?php

// app/Http/Middleware/EnsureProfileIsComplete.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (is_null($user->dni) || is_null($user->phone) || is_null($user->birthdate)) {
            return redirect()->route('complete-profile.create');
        }

        return $next($request);
    }
}
