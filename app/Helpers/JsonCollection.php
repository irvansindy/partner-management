<?php

namespace App\Helpers;

class JsonCollection
{
    /**
     * Give success response.
     */
    public static function success($data = null, $message = null, $code = 200)
    {
        $response = [
            'meta' => [
                'code' => $code,
                'status' => 'success',
                'message' => $message,
            ],
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    /**
     * Give error response.
     */
    public static function error($data = null, $message = null, $code = 400)
    {
        $response = [
            'meta' => [
                'code' => $code,
                'status' => 'error',
                'message' => $message,
            ],
            'data' => $data,
        ];

        return response()->json($response, $code);
    }
}
