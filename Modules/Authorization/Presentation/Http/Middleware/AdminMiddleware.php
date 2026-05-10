<?php

namespace Modules\Authorization\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Shared\ApiResponses;
use Modules\Shared\Domain\ValueObjects\UserId;
use Symfony\Component\HttpFoundation\Response;

readonly class AdminMiddleware
{
    use ApiResponses;

    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {
    }

    /**
     * The middleware check if user has admin role or not.
     */

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return self::error('Unauthenticated', 401);
        }
        $roles = $this->roleRepository->findByUserId(new UserId($user->id));

        $hasAdminRole = collect($roles)->contains(
            fn($role) => $role->name() === 'admin'
        );

        if (!$hasAdminRole) {
            return self::error('You do not have permission to perform this action.', 403);
        }

        return $next($request);
    }
}
