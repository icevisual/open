<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * UsedTime message
 */
class UsedTime extends \ProtobufMessage
{
    /* Field index constants */
    const BOTTLE = 1;
    const TIME = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::BOTTLE => array(
            'name' => 'bottle',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::TIME => array(
            'name' => 'time',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::BOTTLE] = null;
        $this->values[self::TIME] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'bottle' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBottle($value)
    {
        return $this->set(self::BOTTLE, $value);
    }

    /**
     * Returns value of 'bottle' property
     *
     * @return string
     */
    public function getBottle()
    {
        $value = $this->get(self::BOTTLE);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'time' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setTime($value)
    {
        return $this->set(self::TIME, $value);
    }

    /**
     * Returns value of 'time' property
     *
     * @return integer
     */
    public function getTime()
    {
        $value = $this->get(self::TIME);
        return $value === null ? (integer)$value : $value;
    }
}
}