<?php

namespace App\Http\Controllers\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user's role is in the allowed roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        // Check if account is active
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['login' => 'Your account has been deactivated.']);
        }

        return $next($request);
    }
}

// Register this middleware in app/Http/Kernel.php:
// protected $middlewareAliases = [
//     'role' => \App\Http\Middleware\CheckRole::class,
// ];