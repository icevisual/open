<?php
namespace SmellOpen\Exceptions;

class BaseException extends \Exception
{

    public $data = [];

    public function getData()
    {
        return $this->data;
    }

    public function __construct($message, $data = [], $code = 777 )
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }
}
