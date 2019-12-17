<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * DevAttrs message
 */
class DevAttrs extends \ProtobufMessage
{
    /* Field index constants */
    const ATTRS = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::ATTRS => array(
            'name' => 'attrs',
            'repeated' => true,
            'type' => '\Proto2\Scentrealm\Simple\DevAttrOption'
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
     * Appends value to 'attrs' list
     *
     * @param \Proto2\Scentrealm\Simple\DevAttrOption $value Value to append
     *
     * @return null
     */
    public function appendAttrs(\Proto2\Scentrealm\Simple\DevAttrOption $value)
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
     * @return \Proto2\Scentrealm\Simple\DevAttrOption[]
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
     * @return \Proto2\Scentrealm\Simple\DevAttrOption
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