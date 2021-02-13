<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseAPI
{
    /**
     * Format Json Response
     * @param array $data
     * @return JsonResponse
     */
    public function formatResponse(array $data): JsonResponse
    {
        switch ($data['type']) {
            case 'success' :
                return $this->success(isset($data['info']) ? $data['info'] : null, isset($data['data']) ? $data['data'] : null, isset($data['code']) ? $data['code'] : 200);
            case 'error' :
                return $this->error(isset($data['info']) ? $data['info'] : null, isset($data['code']) ? $data['code'] : 200);
        }
    }

    /**
     * Core of response
     * @param $message
     * @param null $data
     * @param $statusCode
     * @param bool $isSuccess
     * @return JsonResponse
     */
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true): JsonResponse
    {
        if ($isSuccess) {
            return response()->json(array_filter([
                'message' => $message,
                'data' => $data
            ]), $statusCode);
        } else {
            return response()->json(array_filter([
                'message' => $message,
                'error' => true,
            ]), $statusCode);
        }
    }

    /**
     * Send any success response
     * @param $message
     * @param $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success($message, $data, $statusCode = 200): JsonResponse
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    /**
     * Send any error response
     * @param $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function error($message, $statusCode = 500): JsonResponse
    {
        return $this->coreResponse($message, null, $statusCode, false);
    }


}
