<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * DevAttrOption message
 */
class DevAttrOption extends \ProtobufMessage
{
    /* Field index constants */
    const ATTR = 1;
    const VALUE = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::ATTR => array(
            'name' => 'attr',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::VALUE => array(
            'name' => 'value',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
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
        $this->values[self::ATTR] = null;
        $this->values[self::VALUE] = null;
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
     * Sets value of 'attr' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setAttr($value)
    {
        return $this->set(self::ATTR, $value);
    }

    /**
     * Returns value of 'attr' property
     *
     * @return integer
     */
    public function getAttr()
    {
        $value = $this->get(self::ATTR);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'value' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setValue($value)
    {
        return $this->set(self::VALUE, $value);
    }

    /**
     * Returns value of 'value' property
     *
     * @return string
     */
    public function getValue()
    {
        $value = $this->get(self::VALUE);
        return $value === null ? (string)$value : $value;
    }
}
}