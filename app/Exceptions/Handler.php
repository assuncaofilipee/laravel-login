<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use PDOException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    public function render($request, Throwable $exception)
    {
        $exceptionMap = [
            [$exception instanceof ModelNotFoundException,  'Objeto não encontrado', JsonResponse::HTTP_NOT_FOUND],
            [$exception instanceof NotFoundHttpException,  'Rota não encontrada', JsonResponse::HTTP_NOT_FOUND],
            [$exception instanceof PDOException, 'Ocorreu um erro interno', JsonResponse::HTTP_INTERNAL_SERVER_ERROR]
        ];

        foreach ($exceptionMap as $exceptionItem) {
            if ($exceptionItem[0]) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => $exceptionItem[1]
                    ]
                ], $exceptionItem[2]);
            }
        }

        return parent::render($request, $exception);
    }
}
