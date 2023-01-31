<?php

namespace App\Helpers;

class UnifiedJsonResponse
{
    public static function success(array $data = [], string $message = 'success',  int $statusCode = 200, array $headers = [])
    {
        $body = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($body, $statusCode)->withHeaders($headers);
    }

    public static function error(array $errors = [], string $message = 'error', int $statusCode = 400, array $headers = [])
    {
        $body = [
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ];
        return response()->json($body, $statusCode)->withHeaders($headers);
    }
}
