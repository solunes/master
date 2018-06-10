<?php

namespace Solunes\Master\App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if(!$e instanceof ModelNotFoundException && !$e instanceof HttpException){
            // Activar si está dado de baja
            if (\App::isDownForMaintenance()){
                \Artisan::call('up');
            }
            // Enviar email
            if (config('solunes.error_report') === true && config('app.debug') === false) {
            //if(!$e instanceof HttpException){
                // ENVIAR EMAIL CON LOG DEL ERROR A SOPORTE@SOLUNES.COM
                $app_name = config('app.name');
                if(\Auth::check()){
                    $user = auth()->user()->name;
                } else {
                    $user = 'Usuario no identificado';
                }
                \Mail::send('master::emails.error', ['app_name' => $app_name, 'user' => $user, 'url'=>request()->url(), 'log' => str_replace('#', '<br>#', $e)], function ($m) use($app_name) {
                    $m->to('edumejia30@gmail.com', 'Eduardo Mejia')->subject('Error de sistema en: '.strtoupper($app_name));
                });
            }
        }
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        //check if exception is an instance of ModelNotFoundException.
        if ($e instanceof ModelNotFoundException) {
            // ajax 404 json feedback
            if ($request->ajax()) {
                return response()->json(['error' => 'No encontrado'], $e->getStatusCode());
            }
            return response()->view('master::errors.404', [], 404);
        }
        if ($e instanceof HttpException) {
            // ajax 404 json feedback
            if ($request->ajax()) {
                return response()->json(['error' => 'No encontrado'], $e->getStatusCode());
            }

            if (view()->exists('master::errors.'.$e->getStatusCode())) {
                return response()->view('master::errors.'.$e->getStatusCode(), ['message'=>$e->getMessage()], $e->getStatusCode());
            }
        } else {
            if (config('app.debug') === false) {
                return response()->view('master::errors.500', [], 500);
            }
        }
        return parent::render($request, $e);
    }
}
