<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            // if route is binding model and model not found
            if ($e->getPrevious() instanceof ModelNotFoundException) {
                $modelId = $e->getPrevious()->getIds()[0];
                $tableName = basename($e->getPrevious()->getModel());
                return $this->errorResponse(
                    [],
                    "$tableName with id: $modelId not found in database.",
                    $e->getStatusCode()
                );
            }

            return $this->errorResponse(
                [],
                $e->getMessage(),
                $e->getStatusCode()
            );
        });

        $this->renderable(function (ThrottleRequestsException $e, Request $request) {
            return $this->errorResponse(
                [],
                $e->getMessage() . '. Try again in 60 seconds.',
                $e->getStatusCode()
            );
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            return $this->errorResponse(
                [],
                $e->getMessage(),
                $e->getStatusCode()
            );
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return $this->errorResponse(
            $this->transformErrors($exception),
            $exception->getMessage(),
            $exception->status
        );
    }

    // transform the error messages,
    private function transformErrors(ValidationException $exception): array
    {
        $errors = [];

        foreach ($exception->errors() as $field => $message) {
            $errors[$field] = [
                'field' => $field,
                'message' => $message[0],
            ];
        }

        return $errors;
    }
}
