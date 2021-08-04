<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return $next($request);
        } else {
            $Role = Auth::user()->roles->first();
            return redirect('/' . $Role->name);
        }
    }
}
