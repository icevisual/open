<?php

namespace App\Events;

abstract class Event
{
    
    protected $_event_data = [];
    
    /**
     *
     * @param unknown $data<pre>
     *            [
     *            'way' => '',
     *            'uid' => ''
     *            ]</pre>
     */
    public function __construct($data = [])
    {
        $this->setEventData($data);
    }
    
    public function setEventData($argv1,$argv2 = null){
        if(is_array($argv1) ){
            $this->_event_data = $argv1 + $this->_event_data ;
        }else if ($argv1 instanceof  \ArrayAccess){
            $this->_event_data = (array)$argv1 + $this->_event_data ;
        }else {
            $this->_event_data[$argv1] = $argv2;
        }
    }
    
    public function getEventData($key = null)
    {
        if($key){
            return isset($this->_event_data[$key]) ? $this->_event_data[$key] :false;
        }
        return $this->_event_data;
    }
    
    
}
