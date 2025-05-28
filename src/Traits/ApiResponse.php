<?php

namespace Soukar\Larepo\Traits;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected function successResponse($data, $message = NULL)
    {

        $response = [
            'status'  => 'Success',
            // 'hasErrors' => false,
            'message' => $message,
        ];
        if ($data instanceof LengthAwarePaginator || ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator)) {
            $response['lastPage'] = (int)$data->lastPage();
            $response['currentPage'] = (int)$data->currentPage();
            $response['hasMore'] = (bool)($data->lastPage() > $data->currentPage());
            $response['total'] = (int)$data->total();
            if (is_array($data->getOptions())) {
                $response['options'] = $data->getOptions();
            }
            $response['data'] = $data->items();
        } else {
            $response['data'] = $data;
        }
        return response()->json(
            $response,
            200
        );
    }

    protected function errorResponse($message, $code, $data = [])
    {

        return response()->json(
            [
                'status'  => 'Error',
                // 'hasErrors' => true,
                'message' => $message,
                'data'    => $data,
            ],
            $code
        );
    }

    protected function validationErrorsResponse($validation_errors)
    {
        return response()->json(
            [
                'status'           => 'Error',
                // 'hasErrors' => true,
                'message'          => 'validation errors',
                'data'             => NULL,
                'validationErrors' => $validation_errors,
            ],
            422
        );
    }
}
