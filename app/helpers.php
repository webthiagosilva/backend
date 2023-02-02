<?php

if (! function_exists('apiResponse')) {
    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function apiResponse(string $message, array $data = [], int $code = 200): \Illuminate\Http\JsonResponse
    {
        $responseData = [
            'success' => true,
            'message' => $message,
        ];

        if (!empty($data)) {
            $responseData['data'] = $data;
        }

        return response()->json($responseData, $code);
    }
}
