<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetTenantScope
{
    public function handle(Request $request, Closure $next): Response
    {
        // The BelongsToClinic trait handles global scoping via boot method
        // This middleware ensures the user has a clinic_id set
        if (Auth::check() && !Auth::user()->clinic_id && !Auth::user()->isSuperadmin()) {
            abort(403, 'Akun belum terkait dengan klinik.');
        }

        return $next($request);
    }
}
