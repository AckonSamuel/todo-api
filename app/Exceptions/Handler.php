<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Always return JSON responses for API requests
        if ($request->is('api/*') || $request->expectsJson()) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error.',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found.',
                ], 404);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access.',
                ], 401);
            }

            if ($exception instanceof QueryException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Database query error.',
                ], 400);
            }

            if ($exception instanceof HttpException) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                ], $exception->getStatusCode());
            }

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred.',
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Report exceptions with logging.
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        // Log errors during production
        if (app()->environment('production') && $exception instanceof HttpException) {
            logger()->error('HTTP Error: ' . $exception->getMessage(), [
                'status_code' => $exception->getStatusCode(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
