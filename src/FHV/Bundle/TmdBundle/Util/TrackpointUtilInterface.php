<?php

namespace FHV\Bundle\TmdBundle\Util;

use FHV\Bundle\TmdBundle\Model\TrackpointInterface;

/**
 * Interface for the utility methods like calculating velocity, acceleration, distance etc of
 * gps trackpoints
 * Interface TrackpointUtilInterface
 * @package FHV\Bundle\TmdBundle\Util
 */
interface TrackpointUtilInterface
{
    /**
     * Calculates the distance between two trackpoints and returns them in meters
     * http://www.movable-type.co.uk/scripts/latlong.html
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return float
     */
    public function calcDistance(TrackpointInterface $tp1, TrackpointInterface $tp2);

    /**
     * Calculates the time difference between two trackpoints
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return int
     */
    public function calcTime(TrackpointInterface $tp1, TrackpointInterface $tp2);

    /**
     * Calculates the elevation difference between two trackpoints
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return float
     */
    public function calcElevation(TrackpointInterface $tp1, TrackpointInterface $tp2);

    /**
     * Calculates the velocity from a distance and a time
     *
     * @param float $distance in meters
     * @param int $time in seconds
     *
     * @return float velocity in m/s
     */
    public function calcVelocity($distance, $time);

    /**
     * Calculates acceleration (the difference in two velocity values over time)
     *
     * @param float $currentVelocity in m/s
     * @param int $time in seconds
     * @param float $prevVelocity in m/s
     *
     * @return float
     */
    public function calcAcceleration($currentVelocity, $time, $prevVelocity);
}
