<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

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
    public function render($request, Throwable $exception)
    {
        // Manejo de errores de autenticación
        if ($exception instanceof AuthenticationException) {
            return redirect()->route('login')->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        }

        if ($exception instanceof AuthorizationException) {
            return response()->view('errors.403', ['code' => 403,'message' => 'No tienes permiso para acceder a esta sección.'], 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [
                'code' => 404, 
                'message' => 'Lo sentimos, la página que estás buscando no se encuentra.' 
            ], 404);
        }

        if ($exception instanceof HttpException) {
            \Log::error('Error crítico: ' . $exception->getMessage());
            return response()->view('errors.500', ['code' => 500,'message' => 'Algo salió mal. Estamos trabajando para solucionarlo.'], 500);
        }

        // Manejo de errores de validación
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return redirect()->back()->withErrors($exception->errors())->withInput()->with('custom_message', 'Por favor, revisa los campos marcados y vuelve a intentarlo.');
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return response()->view('errors.429', ['code' => 429,'message' => 'Has realizado demasiadas solicitudes en poco tiempo. Por favor, intenta nuevamente más tarde.'], 429);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException) {
            return response()->view('errors.503', ['code' => 503,'message' => 'El sistema está en mantenimiento. Por favor, vuelve a intentar en unos minutos.'], 503);
        }

        // Si no se maneja ninguna de las excepciones anteriores, llamar al método padre.
        return parent::render($request, $exception);
    }
}
