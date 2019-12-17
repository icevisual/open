<?php
namespace App\Exceptions;

class ValidationException extends BaseException
{

    public function __construct($message, $code = \ErrorCode::LOGIC_ERROR, $data = [])
    {
        parent::__construct($message, $code, $data);
    }
}
