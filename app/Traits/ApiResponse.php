<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     */
    public function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Return a success JSON response for paginated data.
     */
    public function successPaginated($paginator, $resourceClass = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $data = $resourceClass ? $resourceClass::collection($paginator->items()) : $paginator->items();
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'pagination' => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ]
        ], $code);
    }

    /**
     * Return an error JSON response.
     */
    public function error(string $message = 'Error', $errors = null, int $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
