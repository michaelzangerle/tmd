<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Interface for a tracksegment that provides standard properties like start, end, distance, time and more
 * Interface TracksegmentInterface
 * @package FHV\Bundle\TmdBundle\Model
 */
interface TracksegmentInterface
{
    /**
     * Gets the start point
     *
     * @return TrackpointInterface
     */
    public function getStartPoint();

    /**
     * Sets the start point
     *
     * @param TrackpointInterface $startPoint
     */
    public function setStartPoint($startPoint);

    /**
     * Gets the endpoint
     * @return TrackpointInterface
     */
    public function getEndPoint();

    /**
     * Sets the endpoint
     *
     * @param TrackpointInterface $endPoint
     */
    public function setEndPoint($endPoint);

    /**
     * Gets the type
     *
     * @return string
     */
    public function getType();

    /**
     * Sets the type
     *
     * @param string $type
     */
    public function setType($type);

    /**
     * Gets the distance
     *
     * @return float
     */
    public function getDistance();

    /**
     * Sets the distance
     *
     * @param float $distance
     */
    public function setDistance($distance);

    /**
     * Gets the time
     *
     * @return float time of segment in seconds
     */
    public function getTime();

    /**
     * Sets the time
     *
     * @param float $time of segment in seconds
     */
    public function setTime($time);

    /**
     * Returns all trackpoints
     *
     * @return TrackpointInterface[]
     */
    public function getTrackPoints();

    /**
     * Sets multiple trackpoints
     *
     * @param TrackpointInterface[] $trackPoints
     */
    public function setTrackPoints($trackPoints);

    /**
     * Returns a feature by key
     *
     * @param $key
     *
     * @return mixed
     */
    public function getFeature($key);

    /**
     * Sets a feature
     *
     * @param $key
     * @param $value
     */
    public function setFeature($key, $value);

    /**
     * Sets features for a segment but takes also care of basic features like
     * distance, time, start, end, type and trackpoints which will set separately
     *
     * @param array $features
     */
    public function setFeatures(array $features);

    /**
     * Adds a trackpoint
     *
     * @param TrackpointInterface $trackpoint
     */
    public function addTrackpoint(TrackpointInterface $trackpoint);

    /**
     * Gets a result
     *
     * @return Result
     */
    public function getResult();

    /**
     * Sets a result
     *
     * @param Result $result
     */
    public function setResult($result);

    /**
     * Returns all features of the segment
     * @return array
     */
    public function getFeatures();

    /**
     * Append trackpoints to the existing ones
     *
     * @param TrackpointInterface[] $trackPoints
     *
     * @return mixed
     */
    public function addTrackPoints(array $trackPoints);

    /**
     * Returns if the segment is certain or uncertain
     * @return boolean
     */
    public function isCertainSegment();

    /**
     * Sets segment certain or uncertain
     * @param boolean $certainSegment
     */
    public function setCertainSegment($certainSegment);
}
