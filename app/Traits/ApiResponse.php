<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse
{
    public static function success(string $message, array|JsonResource $data = []): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function error(string $message, array|JsonResource $data = []): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $data,
        ];
    }

    public function successResponse(array|JsonResource $data = [], string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json(self::success($message, $data), $code);
    }

    public function errorResponse(array $data = [], string $message = 'Error', int $code = 500): JsonResponse
    {
        if ($code < 100 || $code > 599) {
            $code = 500;
        }
        return response()->json(self::error($message, $data), $code);
    }
}
