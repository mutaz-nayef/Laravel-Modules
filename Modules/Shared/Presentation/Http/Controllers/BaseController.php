<?php

namespace Modules\Shared\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\ApiResponses;


class BaseController extends Controller
{
    use ApiResponses;

    public function handle($callback)
    {
        try {
            $result = $callback();
            return self::ok($result['message'], $result['data'] ?? null);

        } catch (\Throwable $e) {
            return self::error(
                $e->getMessage(),
                $e->status ?? ($e->getCode() === 0 ? 500 : $e->getCode())
            );
        }
    }
}
