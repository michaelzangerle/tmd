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
    public function getMeanAcceleration();

    /**
     * @param float $meanAcceleration
     */
    public function setMeanAcceleration($meanAcceleration);

    /**
     * @return float
     */
    public function getMeanVelocity();

    /**
     * @param float $meanVelocity
     */
    public function setMeanVelocity($meanVelocity);

    /**
     * @return float
     */
    public function getMaxAcceleration();

    /**
     * @param float $maxAcceleration
     */
    public function setMaxAcceleration($maxAcceleration);

    /**
     * @return float
     */
    public function getMaxVelocity();

    /**
     * @param float $maxVelocity
     */
    public function setMaxVelocity($maxVelocity);

    /**
     * @return float
     */
    public function getDistance();

    /**
     * @param float $distance
     */
    public function setDistance($distance);

    /**
     * @return float duration of segment in seconds
     */
    public function getDuration();

    /**
     * @param float $duration of segment in seconds
     */
    public function setDuration($duration);

    /**
     * @return TrackpointInterface[]
     */
    public function getTrackPoints();

    /**
     * @param TrackpointInterface[] $trackPoints
     */
    public function setTrackPoints($trackPoints);

    /**
     * Returns a segment partially as array
     * @return array
     */
    public function toCSVArray();
}
