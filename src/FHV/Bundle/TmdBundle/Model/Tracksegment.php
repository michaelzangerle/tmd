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
     * depending on the analysis type different other
     * features
     * @var array
     */
    protected $features = [];

    /**
     * @var Result
     */
    protected $result;

    /**
     * @param                       $time
     * @param float                 $distance in meters
     * @param TrackpointInterface   $startPoint
     * @param TrackpointInterface   $endPoint
     * @param TrackpointInterface[] $trackPoints
     * @param string                $type of segment
     */
    function __construct(
        $time = 0,
        $distance = 0.0,
        $startPoint = null,
        $endPoint = null,
        $trackPoints = [],
        $type = 'undefined'
    ) {
        $this->features['time'] = $time;
        $this->features['distance'] = round($distance, 2);
        $this->features['type'] = $type;
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
     * Adds a trackpoints
     * @param TrackpointInterface $trackPoint
     */
    public function addTrackPoint($trackPoint)
    {
        if(array_key_exists('trackPoints', $this->features)) {
            $this->features['trackPoints'][] = $trackPoint;
        }
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
        $this->features['trackPoints'] = $trackPoints;
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
        $this->features['startPoint'] = $startPoint;
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
        $this->features['endPoint'] = $endPoint;
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
        $this->features['type'] = $type;
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
        $this->features['distance'] = $distance;
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
        $this->features['time'] = $time;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param Result $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
