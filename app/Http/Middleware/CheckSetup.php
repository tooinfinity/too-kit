<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('testing')) {
            return $next($request);
        }

        $isSetupCompleted = Setting::setupCompleted();

        if (! $isSetupCompleted && ! $request->routeIs('setup*')) {
            return redirect()->route('setup.index');
        }

        if ($isSetupCompleted && $request->routeIs('setup*')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
