<?php

namespace App\Http\Middleware;

use App\Services\Modules\ModuleGate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $moduleSlug): Response
    {
        if (ModuleGate::isDisabled($moduleSlug)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Module [{$moduleSlug}] is not enabled for your organization.",
                    'module' => $moduleSlug,
                ], 403);
            }

            abort(404, "Module [{$moduleSlug}] is not enabled for your organization.");
        }

        return $next($request);
    }
}
