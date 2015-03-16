<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\TmdBundle\Model\Segment;
use FHV\Bundle\TmdBundle\Model\TrackPoint;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class SegmentManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class SegmentManager extends ContainerAware
{
    /**
     * Radius of the earth
     * @var float
     */
    private $radius;

    function __construct($radius)
    {
        $this->radius = $radius;
    }

    /**
     * Returns a segment for the given trackpoints
     * @param  $trackPoints
     * @param string|null $type
     * @return Segment
     */
    public function createSegment(array $trackPoints, $type = null)
    {
        $meanAcceleration = 0;
        $amountOfTrackpoints = count($trackPoints);
        $meanVelocity = 0;
        $maxAcceleration = 0;
        $maxVelocity = 0;
        $distance = 0;
        $time = 0;
        $validTrackpoints = 0;
        $prevVelocity = 0;
        $accTrackPoints = 0;

        for ($i = 0; $i < $amountOfTrackpoints - 1; $i++) {
            $tp1 = new TrackPoint($trackPoints[$i]);
            $tp2 = new TrackPoint($trackPoints[$i + 1]);

            $currentDistance = $this->calcDistance($tp1, $tp2);
            $currentTime = $this->calcTime($tp2->getTime(), $tp1->getTime());

            // sometimes the distance does not match with the time at all
            // skipping these points
            if ($currentTime < 1) { // TODO put into config
                continue;
            }

            $currentVelocity = $this->calcVelocity($currentDistance, $currentTime);
            $currentAcceleration = $this->calcAcceleration($currentVelocity, $currentTime, $prevVelocity);

            $distance += $currentDistance;
            $time += $currentTime;
            $meanVelocity += $currentVelocity;
            $validTrackpoints++;

            if ($currentVelocity > $maxVelocity) {
                $maxVelocity = $currentVelocity;
            }

            if ($currentAcceleration > $maxAcceleration) {
                $maxAcceleration = $currentAcceleration;
            }

            if ($currentAcceleration > 0) {
                $meanAcceleration += $currentAcceleration;
                $accTrackPoints++;
            }

            $prevVelocity = $currentAcceleration;
        }

        return new Segment(
            $meanAcceleration / $accTrackPoints,
            $meanVelocity / $validTrackpoints,
            $maxAcceleration,
            $maxVelocity,
            $time,
            $distance,
            $type
        );
    }

    /**
     * Calculates the distance between two trackpoints and returns them in meters
     * http://www.movable-type.co.uk/scripts/latlong.html
     * @param TrackPoint $tp1
     * @param TrackPoint $tp2
     * @return float
     */
    protected function calcDistance($tp1, $tp2)
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
     * Calculates the time difference
     * @param \DateTime $time1
     * @param \DateTime $time2
     * @return int
     */
    private function calcTime($time1, $time2)
    {
        return $time1->getTimestamp() - $time2->getTimestamp();
    }

    /**
     * Calculates the velocity from a distance and a time
     * @param float $distance in meters
     * @param int $time in seconds
     * @return float velocity in m/s
     */
    private function calcVelocity($distance, $time)
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
    private function calcAcceleration($currentVelocity, $time, $prevVelocity)
    {
        return ($currentVelocity - $prevVelocity) / $time;
    }
}
