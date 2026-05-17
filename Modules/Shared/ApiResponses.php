<?php

namespace Modules\Shared;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected static function ok($message, $data = [])
    {
        return self::success($message, $data);
    }

    protected static function success($message, $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'errors' => '',
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected static function notAuthorized($message): JsonResponse
    {
        return self::error($message);
    }

    protected static function error($errors = [], $statusCode = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => '',
            'errors' => $errors,
            'message' => '',
            'status' => $statusCode
        ], $statusCode);
    }
}
