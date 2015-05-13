<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Interface TracksegmentInterface
 * @package FHV\Bundle\TmdBundle\Model
 */
interface TracksegmentInterface
{
    /**
     * @return Trackpoint
     */
    public function getStartPoint();

    /**
     * @param Trackpoint $startPoint
     */
    public function setStartPoint($startPoint);

    /**
     * @return Trackpoint
     */
    public function getEndPoint();

    /**
     * @param Trackpoint $endPoint
     */
    public function setEndPoint($endPoint);

    /**
     * @return int
     */
    public function getType();

    /**
     * @param int $type
     */
    public function setType($type);

    /**
     * @return float
     */
    public function getDistance();

    /**
     * @param float $distance
     */
    public function setDistance($distance);

    /**
     * @return float time of segment in seconds
     */
    public function getTime();

    /**
     * @param float $time of segment in seconds
     */
    public function setTime($time);

    /**
     * @return TrackpointInterface[]
     */
    public function getTrackPoints();

    /**
     * @param TrackpointInterface[] $trackPoints
     */
    public function setTrackPoints($trackPoints);

    /**
     * Returns a feature by key
     * @param $key
     *
     * @return mixed
     */
    public function getFeature($key);

    /**
     * Sets a feature
     * @param $key
     * @param $value
     */
    public function setFeature($key, $value);
}
