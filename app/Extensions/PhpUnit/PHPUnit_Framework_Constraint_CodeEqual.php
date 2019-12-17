<?php

namespace App\Extensions\PhpUnit;

class PHPUnit_Framework_Constraint_CodeEqual extends PHPUnit_Framework_Constraint_Extension
{
    
    protected $code ; 
    
    public function __construct($codeOrJArray,$failedCallback = null,$successCallback = null)
    {
        $failedCallback || $failedCallback = function ($actual){
            dump($actual);
        };
        parent::__construct($codeOrJArray,$failedCallback,$successCallback);
        if(is_array($codeOrJArray)){
            $this->code = array_get($codeOrJArray, 'code');
        }else {
            $this->code = $codeOrJArray;
        }
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    protected function doMatch($other){
        return $ret = $other == $this->code;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return ' equal to '.$this->exporter->export($this->code).'('.\ErrorCode::detectError($this->code).')';
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other Evaluated value or object.
     *
     * @return string
     */
    protected function failureDescription($other)
    {
        return 'expected '.$other.'('.\ErrorCode::detectError($other).')'.$this->toString().' ';
    }
}
