<?php

namespace Arif\FleetCartApi\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $guard
     * @return JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard='web')
    {
        if (auth($guard)->check()) {
            return $next($request);
        }

        $url = url()->full();

        if (! $request->isMethod('get')) {
            $url = url()->previous();
        }

        session()->put('url.intended', $url);

        if ($request->ajax()) {
            abort(403, 'Unauthenticated.');
        }

        if(strtolower($guard) === 'api'){
            return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->route('login');
    }
}
