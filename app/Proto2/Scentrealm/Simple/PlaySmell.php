<?php
/**
 * Auto generated from Simple.proto.js at 2016-10-27 14:42:39
 *
 * Proto2.Scentrealm.Simple package
 */

namespace Proto2\Scentrealm\Simple {
/**
 * PlaySmell message
 */
class PlaySmell extends \ProtobufMessage
{
    /* Field index constants */
    const CYCLEMODE = 1;
    const STARTAT = 2;
    const CYCLETIME = 3;
    const ACTIONS = 4;
    const TRACE = 5;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::CYCLEMODE => array(
            'name' => 'cycleMode',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::STARTAT => array(
            'name' => 'startAt',
            'repeated' => true,
            'type' => '\Proto2\Scentrealm\Simple\TimePoint'
        ),
        self::CYCLETIME => array(
            'name' => 'cycleTime',
            'required' => false,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::ACTIONS => array(
            'name' => 'actions',
            'repeated' => true,
            'type' => '\Proto2\Scentrealm\Simple\PlayAction'
        ),
        self::TRACE => array(
            'name' => 'trace',
            'repeated' => true,
            'type' => '\Proto2\Scentrealm\Simple\PlayTrace'
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
        $this->values[self::CYCLEMODE] = null;
        $this->values[self::STARTAT] = array();
        $this->values[self::CYCLETIME] = null;
        $this->values[self::ACTIONS] = array();
        $this->values[self::TRACE] = array();
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
     * Sets value of 'cycleMode' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCycleMode($value)
    {
        return $this->set(self::CYCLEMODE, $value);
    }

    /**
     * Returns value of 'cycleMode' property
     *
     * @return integer
     */
    public function getCycleMode()
    {
        $value = $this->get(self::CYCLEMODE);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'startAt' list
     *
     * @param \Proto2\Scentrealm\Simple\TimePoint $value Value to append
     *
     * @return null
     */
    public function appendStartAt(\Proto2\Scentrealm\Simple\TimePoint $value)
    {
        return $this->append(self::STARTAT, $value);
    }

    /**
     * Clears 'startAt' list
     *
     * @return null
     */
    public function clearStartAt()
    {
        return $this->clear(self::STARTAT);
    }

    /**
     * Returns 'startAt' list
     *
     * @return \Proto2\Scentrealm\Simple\TimePoint[]
     */
    public function getStartAt()
    {
        return $this->get(self::STARTAT);
    }

    /**
     * Returns 'startAt' iterator
     *
     * @return \ArrayIterator
     */
    public function getStartAtIterator()
    {
        return new \ArrayIterator($this->get(self::STARTAT));
    }

    /**
     * Returns element from 'startAt' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Proto2\Scentrealm\Simple\TimePoint
     */
    public function getStartAtAt($offset)
    {
        return $this->get(self::STARTAT, $offset);
    }

    /**
     * Returns count of 'startAt' list
     *
     * @return int
     */
    public function getStartAtCount()
    {
        return $this->count(self::STARTAT);
    }

    /**
     * Sets value of 'cycleTime' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setCycleTime($value)
    {
        return $this->set(self::CYCLETIME, $value);
    }

    /**
     * Returns value of 'cycleTime' property
     *
     * @return integer
     */
    public function getCycleTime()
    {
        $value = $this->get(self::CYCLETIME);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Appends value to 'actions' list
     *
     * @param \Proto2\Scentrealm\Simple\PlayAction $value Value to append
     *
     * @return null
     */
    public function appendActions(\Proto2\Scentrealm\Simple\PlayAction $value)
    {
        return $this->append(self::ACTIONS, $value);
    }

    /**
     * Clears 'actions' list
     *
     * @return null
     */
    public function clearActions()
    {
        return $this->clear(self::ACTIONS);
    }

    /**
     * Returns 'actions' list
     *
     * @return \Proto2\Scentrealm\Simple\PlayAction[]
     */
    public function getActions()
    {
        return $this->get(self::ACTIONS);
    }

    /**
     * Returns 'actions' iterator
     *
     * @return \ArrayIterator
     */
    public function getActionsIterator()
    {
        return new \ArrayIterator($this->get(self::ACTIONS));
    }

    /**
     * Returns element from 'actions' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Proto2\Scentrealm\Simple\PlayAction
     */
    public function getActionsAt($offset)
    {
        return $this->get(self::ACTIONS, $offset);
    }

    /**
     * Returns count of 'actions' list
     *
     * @return int
     */
    public function getActionsCount()
    {
        return $this->count(self::ACTIONS);
    }

    /**
     * Appends value to 'trace' list
     *
     * @param \Proto2\Scentrealm\Simple\PlayTrace $value Value to append
     *
     * @return null
     */
    public function appendTrace(\Proto2\Scentrealm\Simple\PlayTrace $value)
    {
        return $this->append(self::TRACE, $value);
    }

    /**
     * Clears 'trace' list
     *
     * @return null
     */
    public function clearTrace()
    {
        return $this->clear(self::TRACE);
    }

    /**
     * Returns 'trace' list
     *
     * @return \Proto2\Scentrealm\Simple\PlayTrace[]
     */
    public function getTrace()
    {
        return $this->get(self::TRACE);
    }

    /**
     * Returns 'trace' iterator
     *
     * @return \ArrayIterator
     */
    public function getTraceIterator()
    {
        return new \ArrayIterator($this->get(self::TRACE));
    }

    /**
     * Returns element from 'trace' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Proto2\Scentrealm\Simple\PlayTrace
     */
    public function getTraceAt($offset)
    {
        return $this->get(self::TRACE, $offset);
    }

    /**
     * Returns count of 'trace' list
     *
     * @return int
     */
    public function getTraceCount()
    {
        return $this->count(self::TRACE);
    }
}
}