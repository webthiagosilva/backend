<?php

namespace App\Exceptions;

use App\Support\LogErrorCode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        $data = [
            'message' => $this->getErrorMessage($exception),
            'track_id' => strtoupper(uniqid()),
            'error_code' => null,
        ];

        switch (true) {
            case $exception instanceof HttpException:
                $statusCode = $exception->getStatusCode();
                break;

            case $exception instanceof ModelNotFoundException:
                $statusCode = Response::HTTP_NOT_FOUND;
                break;

            case $exception instanceof AuthenticationException:
                $statusCode = Response::HTTP_UNAUTHORIZED;
                break;

            case $exception instanceof ValidationException:
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
                $data['errors'] = $exception->errors();
                break;

            case $exception instanceof AuthorizationException:
                $statusCode = Response::HTTP_FORBIDDEN;
                break;
        }

        $data['error_code'] = LogErrorCode::getErrorCodeIdentifier($statusCode);

        Log::debug('Exception', ['data' => array_merge($data, [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ])]);

        return apiResponse($data, $statusCode, false);
    }

    /**
     * Get error message from exception.
     *
     * @param Throwable $exception
     * @return string
     */
    private function getErrorMessage(Throwable $exception): string
    {
        switch (true) {
            case $exception instanceof HttpException:
                return $exception->getMessage();
            case $exception instanceof ModelNotFoundException:
                return 'The requested resource was not found';
            case $exception instanceof AuthenticationException:
                return 'Unauthorized access';
            case $exception instanceof ValidationException:
                return 'Validation error';
            case $exception instanceof AuthorizationException:
                return 'This action is unauthorized';
            default:
                //return 'Internal Server Error';
                return $exception->getMessage();
        }
    }
}
