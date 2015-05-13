<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a track segment
 * Class Tracksegment
 * @package FHV\Bundle\TmdBundle\Model
 */
class Tracksegment implements TracksegmentInterface
{
    /**
     * Array with features
     * Contains basic features like distance and time and
     * depending on the analyzation type different other
     * featuers
     * @var array
     */
    protected $features = [];

    /**
     * @param                       $time
     * @param float                 $distance in meters
     * @param TrackpointInterface   $startPoint
     * @param TrackpointInterface   $endPoint
     * @param TrackpointInterface[] $trackPoints
     * @param int                   $type of segment
     */
    function __construct(
        $time,
        $distance,
        $startPoint,
        $endPoint,
        $trackPoints,
        $type = 5
    ) {
        $this->features['time'] = $time;
        $this->features['distance'] = round($distance, 2);
        $this->features['type'] = $this->getValidType($type);
        $this->features['endPoint'] = $endPoint;
        $this->features['startPoint'] = $startPoint;
        $this->features['trackPoints'] = $trackPoints;
    }

    /**
     * Sets a feature
     *
     * @param $key
     * @param $value
     */
    public function setFeature($key, $value)
    {
        if(is_float($value)){
            $value = round($value, 16);
        }
        $this->features[$key] = $value;
    }

    /**
     * Returns a value of a feature or null if it does not exist
     *
     * @param $key
     *
     * @return mixed
     */
    public function getFeature($key)
    {
        if (array_key_exists($key, $this->features)) {
            return $this->features[$key];
        }

        return null;
    }

    /**
     * @return TrackpointInterface[]
     */
    public function getTrackPoints()
    {
        return $this->features['trackPoints'];
    }

    /**
     * @param TrackpointInterface[] $trackPoints
     */
    public function setTrackPoints($trackPoints)
    {
        $feature['trackpoints'] = $trackPoints;
    }

    /**
     * @return Trackpoint
     */
    public function getStartPoint()
    {
        return $this->features['startPoint'];
    }

    /**
     * @param Trackpoint $startPoint
     */
    public function setStartPoint($startPoint)
    {
        $feature['startPoint'] = $startPoint;
    }

    /**
     * @return Trackpoint
     */
    public function getEndPoint()
    {
        return $this->features['endPoint'];
    }

    /**
     * @param Trackpoint $endPoint
     */
    public function setEndPoint($endPoint)
    {
        $feature['endPoint'] = $endPoint;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->features['type'];
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $feature['type'] = $type;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->features['distance'];
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance)
    {
        $feature['distance'] = $distance;
    }

    /**
     * @return float
     */
    public function getTime()
    {
        return $this->features['time'];
    }

    /**
     * @param float $time
     */
    public function setTime($time)
    {
        $feature['time'] = $time;
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
     * @return string returns string for type
     */
    public function getTypeAsString()
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
