<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasRole('staff')) {
            abort(403, 'Bu alana erişim yetkiniz bulunmamaktadır.');
        }

        if (!$request->user()->is_active) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('lms.account_deactivated')], 403);
            }

            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('lms.account_deactivated'),
            ]);
        }

        return $next($request);
    }
}
