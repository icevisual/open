<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * FeatureReportResponse message
 */
class FeatureReportResponse extends \ProtobufMessage
{
    /* Field index constants */
    const FEATURE = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::FEATURE => array(
            'name' => 'feature',
            'repeated' => true,
            'type' => '\Proto2\Scentrealm\Simple\FeatureReport'
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
        $this->values[self::FEATURE] = array();
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
     * Appends value to 'feature' list
     *
     * @param \Proto2\Scentrealm\Simple\FeatureReport $value Value to append
     *
     * @return null
     */
    public function appendFeature(\Proto2\Scentrealm\Simple\FeatureReport $value)
    {
        return $this->append(self::FEATURE, $value);
    }

    /**
     * Clears 'feature' list
     *
     * @return null
     */
    public function clearFeature()
    {
        return $this->clear(self::FEATURE);
    }

    /**
     * Returns 'feature' list
     *
     * @return \Proto2\Scentrealm\Simple\FeatureReport[]
     */
    public function getFeature()
    {
        return $this->get(self::FEATURE);
    }

    /**
     * Returns 'feature' iterator
     *
     * @return \ArrayIterator
     */
    public function getFeatureIterator()
    {
        return new \ArrayIterator($this->get(self::FEATURE));
    }

    /**
     * Returns element from 'feature' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Proto2\Scentrealm\Simple\FeatureReport
     */
    public function getFeatureAt($offset)
    {
        return $this->get(self::FEATURE, $offset);
    }

    /**
     * Returns count of 'feature' list
     *
     * @return int
     */
    public function getFeatureCount()
    {
        return $this->count(self::FEATURE);
    }
}
}