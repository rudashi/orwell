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

    public function responseException(\Exception $exception) : \Illuminate\Http\JsonResponse
    {
        switch ($exception->getCode()) {
            case 7:
                return $this->responseError('Database Missing.', 400);
            case '42P01':
                return $this->responseError('Table Missing.', 400);
            default:
                return $this->responseError($exception->getMessage(), 400);
        }
    }

    public function response($data, int $statusCode = 200) : \Illuminate\Http\JsonResponse
    {
        return response()->json([ 'data' => $data], $statusCode);
    }

}