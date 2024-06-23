<?php

namespace App\Exceptions;

use Exception;
use Froiden\RestAPI\Exceptions\ApiException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Modules\ExceptionSender\Handler\TelegramHandler;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ApiException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
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
        $this->renderable(function (ApiException $e, $request) {
            return response()->json($e, 403);
        });

        $this->reportable(function (Throwable $e) {
        });

        $this->renderable(function (Exception $e) {
            if ($e->getPrevious() instanceof TokenMismatchException) {
                return redirect()->route('login');
            }
        });

        $this->renderable(function (InvalidSignatureException $e) {
            return response()->view('errors.link-expired', [], 403);
        });
    }

//    public function report(Throwable $exception)
//    {
//        $handler = new TelegramHandler();
//        $handler->handle($exception);
//        if (app()->bound('sentry') && $this->shouldReport($exception) && App::environment('demo')) {
//            app('sentry')->captureException($exception);
//        }
//
//        parent::report($exception);
//    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenMismatchException) {

            return redirect(route('login'))->with('message', 'You page session expired. Please try again');
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => __('validation.givenDataInvalid'),
            'errors' => $exception->errors(),
        ], $exception->status);
    }

}
