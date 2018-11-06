<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public function responseError(string $message, int $statusCode = 400) : \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => $message,
        ], $statusCode);
    }

    public function response($data, int $statusCode = 200) : \Illuminate\Http\JsonResponse
    {
        return response()->json([ 'data' => $data], $statusCode);
    }

}