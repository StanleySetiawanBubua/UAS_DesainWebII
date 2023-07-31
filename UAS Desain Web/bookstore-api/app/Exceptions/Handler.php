<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // membaca konfigurasi, apakah aplikasi menggunakan mode production atau development
        $debug = config('app.debug');
        $message = '';
        $status_code = 500;

        // cek jika eksepsinya dikarenakan model tidak ditemukan
        if ($exception instanceof ModelNotFoundException) {
            $message = 'Resource is not found';
            $status_code = 404;
        }
        // cek jika eksepsinya dikarenakan resource tidak ditemukan
        elseif ($exception instanceof NotFoundHttpException) {
            $message = 'Endpoint is not found';
            $status_code = 404;
        }
        // cek jika eksepsinya dikarenakan method tidak diizinkan
        elseif ($exception instanceof MethodNotAllowedHttpException) {
            $message = 'Method is not allowed';
            $status_code = 405;
        }
        // cek jika eksepsinya dikarenakan kegagalan validasi
        else if ($exception instanceof ValidationException) {
            $validationErrors = $exception->validator->errors()->getMessages();
            $validationErrors = array_map(function ($error) {
                return array_map(function ($message) {
                    return $message;
                }, $error);
            }, $validationErrors);
            $message = $validationErrors;
            $status_code = 405;
        }
        // cek jika eksepsinya dikarenakan kegagalan query
        else if ($exception instanceof QueryException) {
            if ($debug) {
                $message = $exception->getMessage();
            } else {
                $message = 'Query failed to execute';
            }
            $status_code = 500;
        }
        // cek jika eksepsinya dikarenakan HttpResponseException
        elseif ($exception instanceof HttpResponseException) {
            $message = $exception->getResponse()->getStatusCode();
            $status_code = $exception->getResponse()->getStatusCode();
        }

        $rendered = parent::render($request, $exception);
        $status_code = $rendered->getStatusCode();

        if (empty($message)) {
            $message = $exception->getMessage();
        }

        $errors = [];

        if ($debug) {
            $errors['exception'] = get_class($exception);
            $errors['trace'] = explode("\n", $exception->getTraceAsString());
        }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $status_code);
    }
}
