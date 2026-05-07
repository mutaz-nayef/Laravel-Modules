<?php

namespace Modules\Authorization\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Authorization\Application\Actions\ResolveFieldAccessAction;
use Modules\Authorization\Application\DTOs\Input\FieldAccessInputDto;
use Modules\Authorization\Domain\Contracts\FieldGuardServiceInterface;
use Modules\Authorization\Domain\Exceptions\ForbiddenFieldException;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;
use Modules\Shared\ApiResponses;
use Modules\Shared\Domain\ValueObjects\UserId;
use Symfony\Component\HttpFoundation\Response;

readonly class FieldGuardMiddleware
{
    use ApiResponses;

    public function __construct(
        private readonly ResolveFieldAccessAction $resolveFieldAccessAction,
        private readonly FieldGuardServiceInterface $fieldGuardService,
    ) {
    }

    /**
     * Usage: ->middleware('field.guard:posts:edit')
     *
     * On the way IN:  rejects request fields the user cannot write.
     * On the way OUT: strips response fields the user cannot read.
     */

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        if (!$user) {
            return self::error('Unauthenticated', 401);
        }
        $userId = new UserId($user->id);
        $fieldAccessOutput = $this->resolveFieldAccessAction->execute(
            new FieldAccessInputDto(
                userId: $userId,
                permissionName: $permission
            )
        );
        $fieldPermissions = new FieldPermissions(
            readable: $fieldAccessOutput->readableFields,
            writable: $fieldAccessOutput->writableFields,
        );
        // --- GUARD reject forbidden write fields BEFORE the request hits the controller ---
        if ($request->isMethod('POST') || $request->isMethod('PATCH') || $request->isMethod('PUT')) {
            try {
                $this->fieldGuardService->guard($request->all(), $fieldPermissions);
            } catch (ForbiddenFieldException $e) {
                return self::error([$e->getMessage(), $e->forbiddenFields()], 403);
            }
        }
        // --- Pass to controller ---
        $response = $next($request);
        // --- FILTER unreadable fields from JSON responses ---
        if ($response instanceof JsonResponse) {
            $response = $this->filterResponse($response, $fieldPermissions);
        }
        return $response;
    }

    private function filterResponse(JsonResponse $response, FieldPermissions $fieldPermissions): JsonResponse
    {
        $data = $response->getData(true); // decode to array
        // Handle both {"data":{...}} and flat {"id":1, ...} structure
        if (isset($data['data'])) {
            // Collection: Filter each item
            if (isset($data['data'][0])) {
                $data['data'] = array_map(fn($item) => $fieldPermissions->filterReadable($item), $data['data']);
            } else {
                // Single resource
                $data['data'] = $fieldPermissions->filterReadable($data['data']);
            }

        } else {
            // Flat structure
            $data['data'] = $fieldPermissions->filterReadable($data);
        }
        return response()->json($data, $response->getStatusCode());
    }
}
