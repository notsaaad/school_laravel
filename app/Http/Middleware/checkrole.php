<?php

namespace App\Http\Middleware;

use App\Models\role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkrole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {



        $user_role = role::find(auth()->user()->role_id);


        if (is_null($user_role)) {
            abort(403, 'لا يوجد لديك صلاحية');
        }


        if (!in_array($role, (json_decode($user_role->permissions)))) {
            abort(403, 'لا يوجد لديك صلاحية ');
        }

        return $next($request);
    }
}
