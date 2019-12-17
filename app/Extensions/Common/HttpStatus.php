<?php
namespace App\Extensions\Common;

class HttpStatus
{

    /**
     * 1×× Informational
     * 
     * @var int
     */
    const HTTP_1 = 1;

    /**
     * 100 Continue
     * 
     * @var int
     */
    const HTTP_100 = 100;

    /**
     * 101 Switching Protocols
     * 
     * @var int
     */
    const HTTP_101 = 101;

    /**
     * 102 Processing
     * 
     * @var int
     */
    const HTTP_102 = 102;

    /**
     * 2×× Success
     * 
     * @var int
     */
    const HTTP_2 = 2;

    /**
     * 200 OK
     * 
     * @var int
     */
    const HTTP_200 = 200;

    /**
     * 201 Created
     * 
     * @var int
     */
    const HTTP_201 = 201;

    /**
     * 202 Accepted
     * 
     * @var int
     */
    const HTTP_202 = 202;

    /**
     * 203 Non-authoritative Information
     * 
     * @var int
     */
    const HTTP_203 = 203;

    /**
     * 204 No Content
     * 
     * @var int
     */
    const HTTP_204 = 204;

    /**
     * 205 Reset Content
     * 
     * @var int
     */
    const HTTP_205 = 205;

    /**
     * 206 Partial Content
     * 
     * @var int
     */
    const HTTP_206 = 206;

    /**
     * 207 Multi-Status
     * 
     * @var int
     */
    const HTTP_207 = 207;

    /**
     * 208 Already Reported
     * 
     * @var int
     */
    const HTTP_208 = 208;

    /**
     * 226 IM Used
     * 
     * @var int
     */
    const HTTP_226 = 226;

    /**
     * 3×× Redirection
     * 
     * @var int
     */
    const HTTP_3 = 3;

    /**
     * 300 Multiple Choices
     * 
     * @var int
     */
    const HTTP_300 = 300;

    /**
     * 301 Moved Permanently
     * 
     * @var int
     */
    const HTTP_301 = 301;

    /**
     * 302 Found
     * 
     * @var int
     */
    const HTTP_302 = 302;

    /**
     * 303 See Other
     * 
     * @var int
     */
    const HTTP_303 = 303;

    /**
     * 304 Not Modified
     * 
     * @var int
     */
    const HTTP_304 = 304;

    /**
     * 305 Use Proxy
     * 
     * @var int
     */
    const HTTP_305 = 305;

    /**
     * 307 Temporary Redirect
     * 
     * @var int
     */
    const HTTP_307 = 307;

    /**
     * 308 Permanent Redirect
     * 
     * @var int
     */
    const HTTP_308 = 308;

    /**
     * 4×× Client Error
     * 
     * @var int
     */
    const HTTP_4 = 4;

    /**
     * 400 Bad Request
     * 
     * @var int
     */
    const HTTP_400 = 400;

    /**
     * 401 Unauthorized
     * 
     * @var int
     */
    const HTTP_401 = 401;

    /**
     * 402 Payment Required
     * 
     * @var int
     */
    const HTTP_402 = 402;

    /**
     * 403 Forbidden
     * 
     * @var int
     */
    const HTTP_403 = 403;

    /**
     * 404 Not Found
     * 
     * @var int
     */
    const HTTP_404 = 404;

    /**
     * 405 Method Not Allowed
     * 
     * @var int
     */
    const HTTP_405 = 405;

    /**
     * 406 Not Acceptable
     * 
     * @var int
     */
    const HTTP_406 = 406;

    /**
     * 407 Proxy Authentication Required
     * 
     * @var int
     */
    const HTTP_407 = 407;

    /**
     * 408 Request Timeout
     * 
     * @var int
     */
    const HTTP_408 = 408;

    /**
     * 409 Conflict
     * 
     * @var int
     */
    const HTTP_409 = 409;

    /**
     * 410 Gone
     * 
     * @var int
     */
    const HTTP_410 = 410;

    /**
     * 411 Length Required
     * 
     * @var int
     */
    const HTTP_411 = 411;

    /**
     * 412 Precondition Failed
     * 
     * @var int
     */
    const HTTP_412 = 412;

    /**
     * 413 Payload Too Large
     * 
     * @var int
     */
    const HTTP_413 = 413;

    /**
     * 414 Request-URI Too Long
     * 
     * @var int
     */
    const HTTP_414 = 414;

    /**
     * 415 Unsupported Media Type
     * 
     * @var int
     */
    const HTTP_415 = 415;

    /**
     * 416 Requested Range Not Satisfiable
     * 
     * @var int
     */
    const HTTP_416 = 416;

    /**
     * 417 Expectation Failed
     * 
     * @var int
     */
    const HTTP_417 = 417;

    /**
     * 418 I'm a teapot
     * 
     * @var int
     */
    const HTTP_418 = 418;

    /**
     * 421 Misdirected Request
     * 
     * @var int
     */
    const HTTP_421 = 421;

    /**
     * 422 Unprocessable Entity
     * 
     * @var int
     */
    const HTTP_422 = 422;

    /**
     * 423 Locked
     * 
     * @var int
     */
    const HTTP_423 = 423;

    /**
     * 424 Failed Dependency
     * 
     * @var int
     */
    const HTTP_424 = 424;

    /**
     * 426 Upgrade Required
     * 
     * @var int
     */
    const HTTP_426 = 426;

    /**
     * 428 Precondition Required
     * 
     * @var int
     */
    const HTTP_428 = 428;

    /**
     * 429 Too Many Requests
     * 
     * @var int
     */
    const HTTP_429 = 429;

    /**
     * 431 Request Header Fields Too Large
     * 
     * @var int
     */
    const HTTP_431 = 431;

    /**
     * 444 Connection Closed Without Response
     * 
     * @var int
     */
    const HTTP_444 = 444;

    /**
     * 451 Unavailable For Legal Reasons
     * 
     * @var int
     */
    const HTTP_451 = 451;

    /**
     * 499 Client Closed Request
     * 
     * @var int
     */
    const HTTP_499 = 499;

    /**
     * 5×× Server Error
     * 
     * @var int
     */
    const HTTP_5 = 5;

    /**
     * 500 Internal Server Error
     * 
     * @var int
     */
    const HTTP_500 = 500;

    /**
     * 501 Not Implemented
     * 
     * @var int
     */
    const HTTP_501 = 501;

    /**
     * 502 Bad Gateway
     * 
     * @var int
     */
    const HTTP_502 = 502;

    /**
     * 503 Service Unavailable
     * 
     * @var int
     */
    const HTTP_503 = 503;

    /**
     * 504 Gateway Timeout
     * 
     * @var int
     */
    const HTTP_504 = 504;

    /**
     * 505 HTTP Version Not Supported
     * 
     * @var int
     */
    const HTTP_505 = 505;

    /**
     * 506 Variant Also Negotiates
     * 
     * @var int
     */
    const HTTP_506 = 506;

    /**
     * 507 Insufficient Storage
     * 
     * @var int
     */
    const HTTP_507 = 507;

    /**
     * 508 Loop Detected
     * 
     * @var int
     */
    const HTTP_508 = 508;

    /**
     * 510 Not Extended
     * 
     * @var int
     */
    const HTTP_510 = 510;

    /**
     * 511 Network Authentication Required
     * 
     * @var int
     */
    const HTTP_511 = 511;

    /**
     * 599 Network Connect Timeout Error
     * 
     * @var int
     */
    const HTTP_599 = 599;
}