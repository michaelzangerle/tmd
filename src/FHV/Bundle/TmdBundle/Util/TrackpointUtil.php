<?php

namespace FHV\Bundle\TmdBundle\Util;

use FHV\Bundle\TmdBundle\Model\TrackpointInterface;

/**
 * Helper class to calculate distances, velocity, ...
 * Class GPSUtil
 */
class TrackpointUtil implements TrackpointUtilInterface
{
    /**
     * Radius of the planet
     * @var
     */
    private $radius;

    function __construct($radius)
    {
        $this->radius = $radius;
    }

    /**
     * Calculates the distance between two trackpoints and returns them in meters
     * http://www.movable-type.co.uk/scripts/latlong.html
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return float
     */
    public function calcDistance(TrackpointInterface $tp1, TrackpointInterface $tp2)
    {
        $lat1 = deg2rad($tp1->getLat());
        $lat2 = deg2rad($tp2->getLat());
        $long1 = deg2rad($tp2->getLat() - $tp1->getLat());
        $long2 = deg2rad($tp2->getLong() - $tp1->getLong());

        $tmp = sin($long1 / 2) * sin($long1 / 2) + cos($lat1) * cos($lat2) * sin($long2 / 2) * sin($long2 / 2);
        $tmp = 2 * atan2(sqrt($tmp), sqrt(1 - $tmp));

        return $tmp * $this->radius;
    }

    /**
     * Calculates the time difference between two trackpoints
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return int
     */
    public function calcTime(TrackpointInterface $tp1, TrackpointInterface $tp2)
    {
        return abs($tp1->getTime()->getTimestamp() - $tp2->getTime()->getTimestamp());
    }

    /**
     * Calculates the elevation difference between two trackpoints
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return float
     */
    public function calcElevation(TrackpointInterface $tp1, TrackpointInterface $tp2)
    {
        return abs($tp1->getEle() - $tp2->getEle());
    }

    /**
     * Calculates the velocity from a distance and a time
     *
     * @param float $distance in meters
     * @param int   $time in seconds
     *
     * @return float velocity in m/s
     */
    public function calcVelocity($distance, $time)
    {
        return $distance / $time;
    }

    /**
     * Calculates acceleration (the difference in two velocity values over time)
     *
     * @param float $currentVelocity in m/s
     * @param int   $time in seconds
     * @param float $prevVelocity in m/s
     *
     * @return float
     */
    public function calcAcceleration($currentVelocity, $time, $prevVelocity)
    {
        if ($time > 0) {
            return abs(($currentVelocity - $prevVelocity) / $time);
        }

        return 0;
    }
}
