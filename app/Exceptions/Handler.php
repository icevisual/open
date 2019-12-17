<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Services\Log\ServiceLog;
use App\Exceptions\ValidationException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        
        \App\Exceptions\ServiceException::class,
        \App\Exceptions\ValidationException::class,
        \App\Exceptions\JsonpException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e            
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    public function render11($request, Exception $e)
    {
        
        //设置HTTP 返回码
        
        // JOSN JOSNP PAGE
        
        $settings = [
            'json' => [
                NotFoundHttpException::class => [
                    9001, 'Not Found', [], \HttpStatus::HTTP_404
                ],
                MethodNotAllowedHttpException::class => [
                    9001, 'Method Not Allowed', [], \HttpStatus::HTTP_405
                ],
                \ReflectionException::class => [
                    9001, 'Gone', [], \HttpStatus::HTTP_410
                ],
                ValidationException::class => function() use($e){
                    return [$e->getCode(), $e->getMessage(), $e->getData(), \HttpStatus::HTTP_409];
                },
                JsonpException::class => function(){
                    $callback = \Input::get('callback');
                    $response = array(
                        'status' => $e->getCode(),
                        'message' => $e->getMessage()
                    );
                    header("Content-type: application/json");
                    if ($callback) {
                        echo $callback . '(' . json_encode($response) . ')';
                        exit();
                    }
                    return \Response::json($response);
                },
            ]
        ];
        
        
        
        
        $settings = [
            NotFoundHttpException::class => [
                9001, 'Not Found', [], \HttpStatus::HTTP_404
            ],
            MethodNotAllowedHttpException::class => [
                9001, 'Method Not Allowed', [], \HttpStatus::HTTP_405
            ],
            \ReflectionException::class => [
                9001, 'Gone', [], \HttpStatus::HTTP_410
            ],
            ValidationException::class => function() use($e){
                return [$e->getCode(), $e->getMessage(), $e->getData(), \HttpStatus::HTTP_409];
            },
            JsonpException::class => function(){
                $callback = \Input::get('callback');
                $response = array(
                    'status' => $e->getCode(),
                    'message' => $e->getMessage()
                );
                header("Content-type: application/json");
                if ($callback) {
                    echo $callback . '(' . json_encode($response) . ')';
                    exit();
                }
                return \Response::json($response);
            },
        ];
        
        foreach ($settings as $k =>  $v){
            if($e instanceof $k){
                if(is_array($v)){
                    return call_user_func_array([\JsonReturn::class,'json'], $v);
                }else if($v instanceof  \Closure){
                    return call_user_func_array([\JsonReturn::class,'json'], $v());
                }
            }
        }
        
        $msg = $e->getMessage();
        $code = $e->getCode();
        
        // SQL错误
        if ($e instanceof QueryException || $code == 0) {
            return $this->logException($request, $e);
        }
        // 偶数错误(非用户输入错误)
        if ($code % 2 == 0) {
//             $this->logException($request, $e);
        }
        
        $httpStatusCode = 400;
        if (strpos($msg, '@') !== false) {
            list ($msg, $httpStatusCode) = explode('@', $msg);
        }
        
        if(\App::environment('local')){
            return parent::render($request, $e);
        }
        
        return response()->json([
            'msg' => $msg,
            'code' => $code
        ], $httpStatusCode);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Exception $e            
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $wantHttpStatus = $request->input('whs','no');
        \App\Http\Controllers\Controller::setHeader();
        $httpStatus = $e->getCode() == \JsonReturn::STATUS_OK ? \HttpStatus::HTTP_200 : \HttpStatus::HTTP_400;
        $wantHttpStatus == 'no' && $httpStatus = \HttpStatus::HTTP_200;
        
        
        
        
        
        
        if ($e instanceof \App\Exceptions\ServiceException) {
            return \JsonReturn::json($e->getCode(),$e->getMessage(), $e->getData(),$httpStatus);
        }
        if ($e instanceof \App\Exceptions\ValidationException) {
            return \JsonReturn::json($e->getCode(),$e->getMessage(), $e->getData(),$httpStatus);
        }
        if ($e instanceof \App\Exceptions\JsonpException) {
            return \JsonReturn::jsonp($e->getCode(),$e->getMessage(), $e->getData(),$httpStatus);
        }
        $wantHttpStatus == 'no' && $httpStatus = \HttpStatus::HTTP_500;
        
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            if ($request->ajax() || $request->wantsJson()) {
                return \JsonReturn::json($e->getCode(),$e->getMessage(), [],$httpStatus);
            }else{
                return redirect('/404');
            }
        }
        
        if ($e instanceof TokenMismatchException) {
            if ($request->ajax() || $request->wantsJson()) {
                return \JsonReturn::json(\ErrorCode::UNAUTHORIZED,'error', [],$httpStatus);
            }else{
                return redirect(route('developer'));
            }
        }
        
        if (! \Config::get('app.debug')) {
            return \JsonReturn::json($e->getCode(),'系统错误', [],$httpStatus);
        }
        return parent::render($request, $e);
    }
}
