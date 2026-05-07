<?php

namespace Modules\Authorization\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Authorization\Application\Actions\CheckPermissionAction;
use Modules\Authorization\Application\DTOs\Input\CheckPermissionInputDto;
use Modules\Authorization\Domain\ValueObjects\ResourceAttributes;
use Modules\Shared\ApiResponses;
use Modules\Shared\Domain\ValueObjects\UserId;
use Symfony\Component\HttpFoundation\Response;

readonly class AbacMiddleware
{
    use ApiResponses;

    public function __construct(
        private readonly CheckPermissionAction $checkPermissionAction
    ) {
    }

    /**
     * Usage on route: ->middleware('abac:posts:edit')
     *
     * The middleware automatically extract resource attributes from:
     * 1. Route model binding (e.g. {post} -> $request->route('post'))
     * 2. Request body fields (owner_id, status, amount, etc.)
     */

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        if (!$user) {
            return self::error('Unauthenticated', 401);
        }
        $resource = $this->buildResourceAttributes($request);
        $output = $this->checkPermissionAction->execute(
            new CheckPermissionInputDto(
                userId: new UserId($user->id),
                permissionName: $permission,
                resource: $resource
            )
        );
        if (!$output->allowed) {
            return self::error(['Forbidden.', $output->reason], 403);
        }

        return $next($request);
    }

    /**
     * Extract resource context from the current request.
     * Checks route-bound model first, then falls back to request data.
     */
    private function buildResourceAttributes(Request $request): ResourceAttributes
    {
        $attributes = [];

        foreach ($request->route()->parameters() as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $attributes = array_merge($attributes, $value->toArray());
                break;
            }
        }
        // Also merge any explicit fields from the request body
        $attributes = array_merge($attributes, $request->only([
            'owner_id',
            'status',
            'amount',
            'type'
        ]));
        return ResourceAttributes::from($attributes);
    }
}
