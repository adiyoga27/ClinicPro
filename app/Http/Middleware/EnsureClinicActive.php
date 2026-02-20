<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->clinic) {
            abort(403, 'Akun tidak terkait dengan klinik manapun.');
        }

        if (!$user->clinic->isActive()) {
            abort(403, 'Klinik Anda telah diblokir. Hubungi administrator.');
        }

        if (!$user->clinic->hasActiveSubscription()) {
            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
