<?php

namespace Modules\Authentication\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Domain\Exceptions\YouCannotStayLoggedInAtThisTime;
use Modules\Authentication\Infrastructure\Services\Security\AuthUserService;
use Modules\Shared\ApiResponses;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanStayLoggedIn
{
    use ApiResponses;

    public function __construct(protected AuthUserService $authService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->authService->ensureUserCanStayLoggedIn($request->user());
        } catch (YouCannotStayLoggedInAtThisTime $e) {
            return self::error($e->getMessage(), 403);
        }
        return $next($request);
    }
}
