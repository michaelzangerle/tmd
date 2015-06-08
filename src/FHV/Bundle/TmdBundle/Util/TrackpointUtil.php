<?php

namespace FHV\Bundle\TmdBundle\Util;

use FHV\Bundle\TmdBundle\Model\BoundingBox;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use FHV\Bundle\TmdBundle\Model\TrackpointInterface;

/**
 * Helper class to calculate distances, velocity, ...
 * Class TrackpointUtil
 * @package FHV\Bundle\TmdBundle\Util
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
     * @param int $time in seconds
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
     * @param int $time in seconds
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

    /**
     * Calculates and returns a bounding box for trackpoint
     *
     * @param TrackpointInterface $tp
     * @param float $distance in meters
     *
     * @return BoundingBox
     */
    public function getBoundingBox(TrackpointInterface $tp, $distance)
    {
        $top = $this->getDistantCoordinate($tp, $distance, 0);
        $right = $this->getDistantCoordinate($tp, $distance, 90);
        $bottom = $this->getDistantCoordinate($tp, $distance, 180);
        $left = $this->getDistantCoordinate($tp, $distance, -90);

        return new BoundingBox($top->getLat(), $bottom->getLat(), $left->getLong(), $right->getLong());
    }

    /**
     * Returns a coordinate with a certain distance and bearing from the given coordinate
     * http://www.movable-type.co.uk/scripts/latlong.html
     *
     * @param TrackpointInterface $tp
     * @param float $distance in meters
     * @param int $bearing in degrees
     *
     * @return TrackpointInterface
     */
    public function getDistantCoordinate(TrackpointInterface $tp, $distance, $bearing)
    {
        $lat1 = deg2rad($tp->getLat());
        $long1 = deg2rad($tp->getLong());
        $bearing = deg2rad($bearing);
        $angularDistance = $distance / $this->radius;
        $cosAngularDistance = cos($angularDistance);
        $sinAngularDistance = sin($angularDistance);
        $sinLat1 = sin($lat1);
        $cosLat1 = cos($lat1);

        $lat2 = asin(
            $sinLat1 * $cosAngularDistance +
            $cosLat1 * $sinAngularDistance * cos($bearing)
        );

        $long2 = $long1 + atan2(
                sin($bearing) * $sinAngularDistance * $cosLat1,
                $cosAngularDistance - $sinLat1 * sin($lat2)
            );

        return new Trackpoint(rad2deg($lat2), rad2deg($long2));
    }
}
