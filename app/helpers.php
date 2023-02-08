<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('apiResponse')) {
    function apiResponse($data, $status = 200, $success = true): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data
        ], $status);
    }
}
