<?php

if (!function_exists('errorResponse')) {
    function errorResponse($message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => 'true',
            'message' => $message
        ], 400);
    }
}
