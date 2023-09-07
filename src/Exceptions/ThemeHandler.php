<?php

namespace BytePlatform\Exceptions;

use BytePlatform\Facades\Theme;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ThemeHandler extends ExceptionHandler
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
     * Get the view used to render HTTP exceptions.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return string|null
     */
    protected function getHttpExceptionView(HttpExceptionInterface $e)
    {

        Theme::reTheme();
        
        $view = apply_filters(PLATFORM_EXCEPTIONS_HANDLER, null);
        if ($view && view()->exists($view)) return $view;

        $view = 'errors.' . $e->getStatusCode();
        if (view()->exists('theme::' . $view))
            return ('theme::' . $view);

        $view = substr($view, 0, -2) . 'xx';

        if (view()->exists('theme::' . $view))
            return ('theme::' . $view);

        return parent::getHttpExceptionView($e);
    }
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
}
