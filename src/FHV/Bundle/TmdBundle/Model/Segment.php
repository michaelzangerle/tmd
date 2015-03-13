<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a track segment
 * Class Segment
 * @package FHV\Bundle\TmdBundle\Model
 */
class Segment
{
    public static $ATTRIBUTES = array(
        'distance',
        'mean velocity',
        'mean acceleration',
        'max velocity',
        'max acceleration',
        'type'
    );

    public static $TYPE_UNDEFINED = 1;
    public static $TYPE_DRIVE = 2;
    public static $TYPE_BUS = 3;
    public static $TYPE_TRAIN = 4;
    public static $TYPE_WALK = 5;
    public static $TYPE_BIKE = 6;

    /**
     * @var float m/s2
     */
    private $meanAcceleration;

    /**
     * @var float m/s
     */
    private $meanVelocity;

    /**
     * @var float  m/s2
     */
    private $maxAcceleration;

    /**
     * @var float m/s
     */
    private $maxVelocity;

    /**
     * @var int seconds
     */
    private $time;

    /**
     * @var float in meters
     */
    private $distance;

    /**
     * Type of transport mode
     * @var int
     */
    private $type;

    /**
     * @param float $meanAcceleration in m/s
     * @param float $meanVelocity in m/s
     * @param float $maxAcceleration in m/s
     * @param float $maxVelocity in m/s
     * @param float $time in seconds
     * @param float $distance in meters
     * @param int $type of segment
     */
    function __construct(
        $meanAcceleration,
        $meanVelocity,
        $maxAcceleration,
        $maxVelocity,
        $time,
        $distance,
        $type = 5
    ) {
        $this->meanAcceleration = $meanAcceleration;
        $this->meanVelocity = $meanVelocity;
        $this->maxAcceleration = $maxAcceleration;
        $this->maxVelocity = $maxVelocity;
        $this->time = $time;
        $this->distance = $distance;
        $this->type = $this->getValidType($type);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMeanAcceleration()
    {
        return $this->meanAcceleration;
    }

    /**
     * @param mixed $meanAcceleration
     */
    public function setMeanAcceleration($meanAcceleration)
    {
        $this->meanAcceleration = $meanAcceleration;
    }

    /**
     * @return mixed
     */
    public function getMeanVelocity()
    {
        return $this->meanVelocity;
    }

    /**
     * @param mixed $meanVelocity
     */
    public function setMeanVelocity($meanVelocity)
    {
        $this->meanVelocity = $meanVelocity;
    }

    /**
     * @return mixed
     */
    public function getMaxAcceleration()
    {
        return $this->maxAcceleration;
    }

    /**
     * @param mixed $maxAcceleration
     */
    public function setMaxAcceleration($maxAcceleration)
    {
        $this->maxAcceleration = $maxAcceleration;
    }

    /**
     * @return mixed
     */
    public function getMaxVelocity()
    {
        return $this->maxVelocity;
    }

    /**
     * @param mixed $maxVelocity
     */
    public function setMaxVelocity($maxVelocity)
    {
        $this->maxVelocity = $maxVelocity;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Validates the given type and returns a valid type
     * @param string | int $type
     * @return bool|int
     */
    private function getValidType($type)
    {
        if (is_string($type)) {
            switch (strtolower($type)) {
                case 'walk':
                    return static::$TYPE_WALK;
                case 'bus':
                    return static::$TYPE_BUS;
                case 'train':
                    return static::$TYPE_TRAIN;
                case 'car':
                    return static::$TYPE_DRIVE;
                case 'bike':
                    return static::$TYPE_BIKE;
                default:
                    return static::$TYPE_UNDEFINED;
            }
        } else {
            if (is_int($type)) {
                if ($type > 0 && $type < 7) {
                    return $type;
                }
            }
        }

        return static::$TYPE_UNDEFINED;
    }

    /**
     * Returns the segment as array
     * @return array
     */
    public function toArray()
    {
        return array(
            $this->getDistance(),
            $this->getMeanVelocity(),
            $this->getMeanAcceleration(),
            $this->getMaxVelocity(),
            $this->getMaxAcceleration(),
            $this->getTypeAsString()
        );
    }

    private function getTypeAsString()
    {
        switch ($this->getType()) {
            case 1:
                return 'undefined';
            case 2:
                return 'car';
            case 3:
                return 'bus';
            case 4:
                return 'train';
            case 5:
                return 'walk';
            case 6:
                return 'bike';

        }
    }
}
