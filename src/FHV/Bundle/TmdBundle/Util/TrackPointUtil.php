<?php

namespace FHV\Bundle\TmdBundle\Util;

use FHV\Bundle\TmdBundle\Model\TrackPoint;

/**
 * Helper class to calculate distances, velocity, ...
 * Class GPSUtil
 */
class TrackPointUtil {

    /**
     * Radius of earth
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
     * @param TrackPoint $tp1
     * @param TrackPoint $tp2
     * @return float
     */
    public function calcDistance($tp1, $tp2)
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
     * @param TrackPoint $tp1
     * @param TrackPoint $tp2
     * @return int
     */
    public function calcTime($tp1, $tp2)
    {
        return $tp1->getTime()->getTimestamp() - $tp2->getTime()->getTimestamp();
    }

    /**
     * Calculates the velocity from a distance and a time
     * @param float $distance in meters
     * @param int $time in seconds
     * @return float velocity in m/s
     */
    public function calcVelocity($distance, $time)
    {
        return $distance / $time;
    }

    /**
     * Calculates the difference in two velocity values
     * @param float $currentVelocity in m/s
     * @param $time
     * @param float $prevVelocity in m/s
     * @return float
     */
    public function calcAcceleration($currentVelocity, $time, $prevVelocity)
    {
        return ($currentVelocity - $prevVelocity) / $time;
    }
}
