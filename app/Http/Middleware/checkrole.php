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
    public function handle(Request $request, Closure $next, $roles)
    {
        $user_role = role::find(auth()->user()->role_id);

        if (is_null($user_role)) {
            abort(403, 'لا يوجد لديك صلاحية');
        }

        $user_permissions = json_decode($user_role->permissions ?? '[]', true);

        // دعم صلاحيات متعددة: show_orders|order_payment|something_else
        $required_roles = explode('|', $roles);

        $has_permission = count(array_intersect($required_roles, $user_permissions)) > 0;

        if (! $has_permission) {
            abort(403, 'لا يوجد لديك صلاحية');
        }

        return $next($request);
    }
}
