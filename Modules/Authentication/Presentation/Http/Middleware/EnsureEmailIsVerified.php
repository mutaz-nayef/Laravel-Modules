<?php

namespace Modules\Authentication\Presentation\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Modules\Shared\ApiResponses;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    use ApiResponses;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())) {
            return self::error('Your email address is not verified.', 403);
        }
        return $next($request);
    }
}
