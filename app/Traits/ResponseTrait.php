<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * @param $status_code
     * @param $success
     * @param $key
     * @param $value
     * @param $extra
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function apiResponse(string $response_status, string $response_code, bool $success, string $message, $key, $value, array $extra = [])
    {
        $response = [];
        $response['response_status'] = $response_status;
        $response['response_code'] = $response_code;
        $response['success'] = $success;
        $response['message'] = $message;
        if ($response_code == 422 && gettype($value) == 'object' && get_class($value) == 'Illuminate\Support\MessageBag') {
            $errors = [];
            foreach ($value->toArray() as $attr => $value_errors) {
                $errors[$attr] = $value_errors[0];
            }
            $response['errors'] = $errors;
        } else {
            $response[$key] = $value;
        }
        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }
        return response()->json($response)->setStatusCode($response_code);
    }

    /**
     * @param $status_code
     * @param $success
     * @param $key
     * @param $value
     * @param $extra
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function apiErrorResponse(object|null $exception, int $status_code = JsonResponse::HTTP_INTERNAL_SERVER_ERROR, string $message = null, array $extra = []) {
        // exception are also reported in some repositories... Watch out
        if( !empty($exception) ) report($exception);
        if( empty($exception) ){
            $message = $message ?: "Something went wrong.";
        }
        $response =
        ['response_status' => "error", 'response_code' => $status_code,'success' => false, 'message' => ($message ?: $exception->getMessage())];
        /*[
            'status_code' => $status_code,
            'success' => false,
            'message' => ($message ?: $exception->getMessage())
        ];*/
        if (!empty($extra)) {
            $response = array_merge($response, $extra);
        }
        return response()->json($response, $status_code);
    }
}
