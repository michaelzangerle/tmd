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
        $segment = new Segment();
        $meanAcceleration = 0;
        $amountOfTrackpoints = count($trackPoints);
        $meanVelocity = 0;
        $maxAcceleration = 0;
        $maxVelocity = 0;
        $distance = 0;
        $time = 0;
        $counter = 0;

        if ($type) {
            $segment->setType($type);
        }

        for ($i = 0; $i < $amountOfTrackpoints - 1; $i++) {
            $tp1 = new TrackPoint($trackPoints[$i]);
            $tp2 = new TrackPoint($trackPoints[$i + 1]);

            $currentDistance = $this->calcDistance($tp1, $tp2);
            $currentTime = $tp2->getTime()->getTimestamp() - $tp1->getTime()->getTimestamp();

            // sometimes the distance does not match with the time at all
            // skipping these points
            if ($currentTime <= 1) {
                continue;
            }
            $currentTime = $currentTime > 0 ? $currentTime : 1;
            $currentVelocity = $currentDistance / $currentTime;

            $distance += $currentDistance;
            $time += $currentTime;
            $meanVelocity += $currentVelocity;
            $counter++;

            if ($currentVelocity > $maxVelocity) {
                $maxVelocity = $currentVelocity;
            }
        }

        $segment->setMeanVelocity($meanVelocity / $counter);
        $segment->setDistance($distance);
        $segment->setTime($time);
        $segment->setMaxVelocity($maxVelocity);

        return $segment;
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
}
