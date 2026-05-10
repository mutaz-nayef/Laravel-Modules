<?php

namespace Modules\Authentication\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Authentication\Application\Actions\CheckCanStayLoginAction;
use Modules\Authentication\Application\Actions\LogoutUserAction;
use Modules\Authentication\Application\DTOs\Auth\Input\LogoutInputDto;
use Modules\Shared\ApiResponses;
use Modules\Shared\Domain\ValueObjects\UserId;
use Symfony\Component\HttpFoundation\Response;

class CheckCanStayLoginMiddleware
{
    use ApiResponses;

    public function __construct(
        private readonly CheckCanStayLoginAction $checkCanStayLoginAction,
        private readonly LogoutUserAction $logoutUserAction
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return self::error('Unauthenticated', 401);
        }

        try {
            $canStayLoginAction = $this->checkCanStayLoginAction->execute($user->id);

            if (!$canStayLoginAction) {
                $this->logoutUserAction->execute(new LogoutInputDto(new UserId($user->id)));
                return self::error('User can not stay logged in', 401);
            }
        } catch (\Exception $exception) {
            return self::error($exception->getMessage(), $exception->getCode());
        }

        return $next($request);
    }
}
