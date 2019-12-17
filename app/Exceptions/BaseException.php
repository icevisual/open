<?php
namespace App\Exceptions;

class BaseException extends \Exception
{

    public $data = [];

    public function getData()
    {
        return $this->data;
    }

    /**
     * 
     * @param unknown $message
     * @param number $code
     * @param unknown $data
     */
    public function __construct($message, $code = 9001, $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }
}
