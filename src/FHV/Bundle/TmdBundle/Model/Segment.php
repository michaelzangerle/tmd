<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a track segment
 * Class Segment
 * @package FHV\Bundle\TmdBundle\Model
 */
class Segment
{
    public static $TYPE_WALKING = 'walking';
    public static $TYPE_DRIVING = 'driving';
    public static $TYPE_BUS = 'bus';
    public static $TYPE_TRAIN = 'train';

    private $meanAcceleration;

    private $meanVelocity;

    private $maxAcceleration;

    private $maxVelocity;

    /**
     * Distance in meters
     * @var float
     */
    private $distance;

    /**
     * Type of transport mode
     * @var string
     */
    private $type;

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
}
