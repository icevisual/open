<?php
namespace App\Exceptions;

class JsonpException extends BaseException
{

    public function __construct($message, $code = 9001, $data = [])
    {
        parent::__construct($message, $code, $data);
    }
}