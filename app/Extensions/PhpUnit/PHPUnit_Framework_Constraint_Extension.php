<?php

namespace App\Extensions\PhpUnit;

abstract class PHPUnit_Framework_Constraint_Extension extends \PHPUnit_Framework_Constraint
{
    
    protected $actual ;
    
    protected $failedCallback;
    
    protected $successCallback;
    
    public function __construct($actual = null,$failedCallback = null,$successCallback = null)
    {
        parent::__construct();
        $this->failedCallback = $failedCallback;
        $this->successCallback = $successCallback;
        $this->setActual($actual);
    }

    protected function setActual($actual){
        $this->actual = $actual;
    }
    
    protected function getActual(){
        return $this->actual;
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
        return true;
    }    
    
    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     *
     * @return bool
     */
    protected function matches($other)
    {
        $ret = $this->doMatch($other);
        if(false === $ret){
            $this->fire($this->failedCallback,[$this->getActual()]);
        }else{
            $this->fire($this->successCallback,[$this->getActual()]);
        }
        return $ret;
    }
    
    protected function fire($function,$params){
        
        if($function && is_callable($function)){
            return call_user_func_array($function, $params);
        }
        return false;
    }
    
}
