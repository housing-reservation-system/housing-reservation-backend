<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait ApiResponse
{
    protected function success($data, ?string $message = null, int $code = Response::HTTP_OK, bool $success = true): JsonResponse
    {
        $responseData = [
            'success' => $success,
            'message' => $message,
            'errors'  => null,
        ];

        $paginator = null;

        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $paginator = $data->resource;
            $responseData['data'] = $data->collection;
        } else if ($data instanceof LengthAwarePaginator) {
            $paginator = $data;
            $responseData['data'] = $data->items();
        } else {
            $responseData['data'] = $data;
        }

        if ($paginator) {
            $responseData['meta'] = [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ];
        }

        return response()->json($responseData, $code);
    }

    protected function successMessage(?string $message = null, int $code = Response::HTTP_OK, bool $success = true): JsonResponse
    {
        return $this->success(null, $message, $code, $success);
    }

    protected function error(?string $message = null, int $code, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $code);
    }
}
