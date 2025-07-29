<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash  = ['password', 'password_confirmation'];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : HttpResponse::HTTP_INTERNAL_SERVER_ERROR;

            return response()->json([
                'error' => $e->getMessage(),
            ], $status);
        }

        return parent::render($request, $e);
    }
}
