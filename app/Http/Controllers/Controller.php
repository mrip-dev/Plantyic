<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Universal API response structure
     */
    protected function apiResponse($data = null, $message = null, $errorCode = null, $success = true, $status = 200, $pagination = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'error_code' => $errorCode,
            'data' => $data,
        ];
        if ($pagination) {
            $response['pagination'] = $pagination;
        }
        return response()->json($response, $status);
    }
}
