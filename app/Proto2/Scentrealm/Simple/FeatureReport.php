<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * FeatureReport message
 */
class FeatureReport extends \ProtobufMessage
{
    /* Field index constants */
    const TYPE = 1;
    const ATTRS = 2;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::TYPE => array(
            'name' => 'type',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::ATTRS => array(
            'name' => 'attrs',
            'repeated' => true,
            'type' => '\Proto2\Scentrealm\Simple\FeatureAttr'
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
        $this->values[self::TYPE] = null;
        $this->values[self::ATTRS] = array();
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
     * Sets value of 'type' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setType($value)
    {
        return $this->set(self::TYPE, $value);
    }

    /**
     * Returns value of 'type' property
     *
     * @return integer
     */
    public function getType()
    {
        $value = $this->get(self::TYPE);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'attrs' list
     *
     * @param \Proto2\Scentrealm\Simple\FeatureAttr $value Value to append
     *
     * @return null
     */
    public function appendAttrs(\Proto2\Scentrealm\Simple\FeatureAttr $value)
    {
        return $this->append(self::ATTRS, $value);
    }

    /**
     * Clears 'attrs' list
     *
     * @return null
     */
    public function clearAttrs()
    {
        return $this->clear(self::ATTRS);
    }

    /**
     * Returns 'attrs' list
     *
     * @return \Proto2\Scentrealm\Simple\FeatureAttr[]
     */
    public function getAttrs()
    {
        return $this->get(self::ATTRS);
    }

    /**
     * Returns 'attrs' iterator
     *
     * @return \ArrayIterator
     */
    public function getAttrsIterator()
    {
        return new \ArrayIterator($this->get(self::ATTRS));
    }

    /**
     * Returns element from 'attrs' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Proto2\Scentrealm\Simple\FeatureAttr
     */
    public function getAttrsAt($offset)
    {
        return $this->get(self::ATTRS, $offset);
    }

    /**
     * Returns count of 'attrs' list
     *
     * @return int
     */
    public function getAttrsCount()
    {
        return $this->count(self::ATTRS);
    }
}
}