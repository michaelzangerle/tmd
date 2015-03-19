<?php

namespace FHV\Bundle\TmdBundle\Manager;

use FHV\Bundle\TmdBundle\Model\Segment;
use FHV\Bundle\TmdBundle\Model\SegmentInterface;
use FHV\Bundle\TmdBundle\Model\TrackPoint;
use FHV\Bundle\TmdBundle\Util\TrackPointUtil;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class SegmentManager
 * @package FHV\Bundle\TmdBundle\Manager
 */
class SegmentManager extends ContainerAware
{
    /**
     * Util obj for calculations
     * @var TrackPointUtil
     */
    private $util;

    function __construct(TrackPointUtil $util)
    {
        $this->util = $util;
    }

    /**
     * Returns a segment for the given trackpoints
     * @param array $trackPoints
     * @param string|null $type
     * @return SegmentInterface
     */
    public function createSegment(array $trackPoints, $type = null)
    {
        $meanAcceleration = 0;
        $amountOfTrackPoints = count($trackPoints);
        $meanVelocity = 0;
        $maxAcceleration = 0;
        $maxVelocity = 0;
        $distance = 0;
        $time = 0;
        $validTrackPoints = 0;
        $prevVelocity = 0;
        $accTrackPoints = 0;

        for ($i = 0; $i < $amountOfTrackPoints - 1; $i++) {
            $tp1 = new TrackPoint($trackPoints[$i]);
            $tp2 = new TrackPoint($trackPoints[$i + 1]);

            $currentDistance = $this->util->calcDistance($tp1, $tp2);
            $currentTime = $this->util->calcTime($tp2, $tp1);

            // sometimes the distance does not match with the time at all
            // skipping these points
            if ($currentTime < 1) { // TODO put into config
                continue;
            }

            $currentVelocity = $this->util->calcVelocity($currentDistance, $currentTime);
            $currentAcceleration = $this->util->calcAcceleration($currentVelocity, $currentTime, $prevVelocity);

            $distance += $currentDistance;
            $time += $currentTime;
            $meanVelocity += $currentVelocity;
            $validTrackPoints++;

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
            $meanVelocity / $validTrackPoints,
            $maxAcceleration,
            $maxVelocity,
            $time,
            $distance,
            $trackPoints[0],
            $trackPoints[$amountOfTrackPoints - 1],
            $type
        );
    }
}
