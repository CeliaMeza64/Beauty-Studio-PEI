<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Manejo de errores de autenticación
        if ($exception instanceof AuthenticationException) {
            return redirect()->route('login')->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        }
        if ($exception instanceof AuthorizationException) {
            return response()->view('errors.403', [
                'code' => 403,
                'message' => 'No tienes permiso para acceder a esta sección.',
                'user' => auth()->user()
            ], 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [
                'code' => 404,
                'message' => 'Lo sentimos, la página que estás buscando no se encuentra.',
                'user' => auth()->user()
            ], 404);
        }

        if ($exception instanceof HttpException) {
            \Log::error('Error crítico: ' . $exception->getMessage());
            return response()->view('errors.500', [
                'code' => 500,
                'message' => 'Algo salió mal. Estamos trabajando para solucionarlo.',
                'user' => auth()->user()
            ], 500);
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return response()->view('errors.429', [
                'code' => 429,
                'message' => 'Has realizado demasiadas solicitudes en poco tiempo. Por favor, intenta nuevamente más tarde.',
                'user' => auth()->user()
            ], 429);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException) {
            return response()->view('errors.503', [
                'code' => 503,
                'message' => 'El sistema está en mantenimiento. Por favor, vuelve a intentar en unos minutos.',
                'user' => auth()->user()
            ], 503);
        }

        // Si no se maneja ninguna de las excepciones anteriores, llamar al método padre.
        return parent::render($request, $exception);
    }
}
