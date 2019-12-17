<?php

namespace App\Extensions\PhpUnit;

class PHPUnit_Framework_Constraint_Json extends PHPUnit_Framework_Constraint_Extension
{
    public function __construct($jsonString, $failedCallback = null,$successCallback = null)
    {
        $failedCallback || $failedCallback = function ($actual){
            \Com::debug($actual);
        };
        parent::__construct($jsonString,$failedCallback,$successCallback);
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    protected function doMatch($other)
    {
        $content = $this->actual;
        $json = json_decode($content, 1);
        if (json_last_error() === JSON_ERROR_NONE) {
            if(array_key_exists('code', $json)){
                return true;
            }
        }
        return false;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return ' is json';
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
        return 'the string is a valid json string';
    }
}
