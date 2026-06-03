<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
        //
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error('Application exception captured.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'url' => request()->fullUrl(),
                'user_id' => optional(request()->user())->id,
            ]);
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof AuthenticationException) {
            return redirect()->guest(route('login'))->with('status', 'Please login to continue.');
        }

        if ($e instanceof TokenMismatchException) {
            Log::warning('Session token mismatch or expired form submission.', [
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('login')->with('status', 'Your session expired. Please login and try again.');
        }

        if ($e instanceof ModelNotFoundException) {
            Log::notice('Requested record was not found.', [
                'model' => $e->getModel(),
                'url' => $request->fullUrl(),
            ]);

            return response()->view('errors.404', [], 404);
        }

        if ($e instanceof QueryException) {
            Log::error('Database error.', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);

            $message = str_contains(strtolower($e->getMessage()), 'no such table')
                ? 'The database tables are missing. Please run the migrations and try again.'
                : 'A database error occurred. Please try again or contact the system administrator.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 500);
            }

            return back()->withInput()->withErrors(['database' => $message]);
        }

        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();

            if (in_array($status, [403, 404, 500], true)) {
                return response()->view("errors.{$status}", [], $status);
            }
        }

        if (! config('app.debug')) {
            Log::error('Unhandled server error.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $e);
    }
}
