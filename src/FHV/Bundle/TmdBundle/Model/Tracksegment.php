<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a track segment
 * Class Tracksegment
 * @package FHV\Bundle\TmdBundle\Model
 */
class Tracksegment implements TracksegmentInterface
{
    public static $ATTRIBUTES = array(
        'stoprate',
        'mean velocity',
        'mean acceleration',
        'max velocity',
        'max acceleration',
        'type'
    );

    /**
     * @var float m/s2
     */
    protected $meanAcceleration;

    /**
     * @var float m/s
     */
    protected $meanVelocity;

    /**
     * @var float  m/s2
     */
    protected $maxAcceleration;

    /**
     * @var float m/s
     */
    protected $maxVelocity;

    /**
     * @var float seconds
     */
    protected $duration;

    /**
     * @var float in meters
     */
    protected $distance;

    /**
     * Type of transport mode
     * @var int
     */
    protected $type;

    /**
     * @var TrackpointInterface
     */
    protected $startPoint;

    /**
     * @var TrackpointInterface
     */
    protected $endPoint;

    /**
     * @var TrackpointInterface[]
     */
    protected $trackPoints;

    /**
     * @var float
     */
    protected $stopRate;

    /**
     * @param float                 $meanAcceleration in m/s
     * @param float                 $meanVelocity in m/s
     * @param float                 $maxAcceleration in m/s
     * @param float                 $maxVelocity in m/s
     * @param float                 $duration in seconds
     * @param float                 $distance in meters
     * @param TrackpointInterface   $startPoint
     * @param TrackpointInterface   $endPoint
     * @param TrackpointInterface[] $trackPoints
     * @param                       $stopRate
     * @param int                   $type of segment
     */
    function __construct(
        $meanAcceleration,
        $meanVelocity,
        $maxAcceleration,
        $maxVelocity,
        $duration,
        $distance,
        $startPoint,
        $endPoint,
        $trackPoints,
        $stopRate,
        $type = 5
    ) {
        $this->meanAcceleration = $meanAcceleration;
        $this->meanVelocity = $meanVelocity;
        $this->maxAcceleration = $maxAcceleration;
        $this->maxVelocity = $maxVelocity;
        $this->duration = $duration;
        $this->distance = $distance;
        $this->type = $this->getValidType($type);
        $this->endPoint = $endPoint;
        $this->startPoint = $startPoint;
        $this->trackPoints = $trackPoints;
        $this->stopRate = round($stopRate,16);
    }

    /**
     * @return TrackpointInterface[]
     */
    public function getTrackPoints()
    {
        return $this->trackPoints;
    }

    /**
     * @param TrackpointInterface[] $trackPoints
     */
    public function setTrackPoints($trackPoints)
    {
        $this->trackPoints = $trackPoints;
    }

    /**
     * @return Trackpoint
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * @param Trackpoint $startPoint
     */
    public function setStartPoint($startPoint)
    {
        $this->startPoint = $startPoint;
    }

    /**
     * @return Trackpoint
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * @param Trackpoint $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
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
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Validates the given type and returns a valid type
     *
     * @param string | int $type
     *
     * @return bool|int
     */
    protected function getValidType($type)
    {
        if (is_string($type)) {
            switch (strtolower($type)) {
                case 'walk':
                    return TracksegmentType::WALK;
                case 'bus':
                    return TracksegmentType::BUS;
                case 'train':
                    return TracksegmentType::TRAIN;
                case 'car':
                    return TracksegmentType::DRIVE;
                case 'bike':
                    return TracksegmentType::BIKE;
                default:
                    return TracksegmentType::UNDEFINIED;
            }
        } else {
            if (is_int($type)) {
                if ($type > 0 && $type < 7) {
                    return $type;
                }
            }
        }

        return TracksegmentType::UNDEFINIED;
    }

    /**
     * @return float
     */
    public function getStopRate()
    {
        return $this->stopRate;
    }

    /**
     * @param float $stopRate
     */
    public function setStopRate($stopRate)
    {
        $this->stopRate = round($stopRate,16);
    }

    /**
     * Returns a partial segment as array
     * @return array
     */
    public function toCSVArray()
    {
        return array(
            $this->getStopRate(),
            $this->getMeanVelocity(),
            $this->getMeanAcceleration(),
            $this->getMaxVelocity(),
            $this->getMaxAcceleration(),
            $this->getTypeAsString()
        );
    }

    /**
     * @return string returns string for type
     */
    protected function getTypeAsString()
    {
        switch ($this->getType()) {
            case TracksegmentType::DRIVE:
                return 'car';
            case TracksegmentType::BUS:
                return 'bus';
            case TracksegmentType::TRAIN:
                return 'train';
            case TracksegmentType::WALK:
                return 'walk';
            case TracksegmentType::BIKE:
                return 'bike';
            default:
                return 'undefined';
        }
    }
}
