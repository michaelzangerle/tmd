<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\Segment;
use FHV\Bundle\TmdBundle\Model\SegmentInterface;
use FHV\Bundle\TmdBundle\Model\TrackPoint;
use FHV\Bundle\TmdBundle\Util\TrackPointUtil;

/**
 * Creates a segment from trackpoints
 * Class SegmentFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class SegmentFilter extends AbstractFilter
{
    /**
     * @var int
     */
    protected $minTrackPointsPerSegment;

    /**
     * Util obj for calculations
     * @var TrackPointUtil
     */
    private $util;

    /**
     * Starts a filter and processes the given data
     * @param $data
     * @param $log
     * @throws FilterException
     */
    public function run($data, $log)
    {
        if (isset($data['trackPoints'])) {
            $segment = $this->createSegment($data['trackPoints'], $data['type']);
            $this->write($segment);
        } else {
            throw new InvalidArgumentException('SegmentFilter: Data param should contain trackpoints!');
        }
    }

    function __construct(TrackPointUtil $util, $minTrackPointsPerSegment)
    {
        $this->util = $util;
        $this->minTrackPointsPerSegment = $minTrackPointsPerSegment;
    }

    /**
     * Returns a segment for the given trackpoints
     * @param array $trackPoints
     * @param string|null $type
     * @return SegmentInterface
     * @throws InvalidArgumentException
     */
    public function createSegment(array $trackPoints, $type = null)
    {
        $meanAcceleration = 0;
        $amountOfTrackPoints = count($trackPoints) - 1;
        $meanVelocity = 0;
        $maxAcceleration = 0;
        $maxVelocity = 0;
        $distance = 0;
        $time = 0;
        $prevVelocity = 0;
        $accTrackPoints = 0;

        if ($amountOfTrackPoints +1 >= $this->minTrackPointsPerSegment) {
            for ($i = 0; $i < $amountOfTrackPoints; $i++) {
                $tp1 = new TrackPoint($trackPoints[$i]);
                $tp2 = new TrackPoint($trackPoints[$i + 1]);

                $currentDistance = $this->util->calcDistance($tp1, $tp2);
                $currentTime = $this->util->calcTime($tp2, $tp1);

                $currentVelocity = $this->util->calcVelocity($currentDistance, $currentTime);
                $currentAcceleration = $this->util->calcAcceleration($currentVelocity, $currentTime, $prevVelocity);

                $distance += $currentDistance;
                $time += $currentTime;
                $meanVelocity += $currentVelocity;

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
                $meanVelocity / $amountOfTrackPoints,
                $maxAcceleration,
                $maxVelocity,
                $time,
                $distance,
                new TrackPoint($trackPoints[0]),
                new TrackPoint($trackPoints[$amountOfTrackPoints]),
                $type
            );
        }

        throw new InvalidArgumentException(
            'SegmentFilter: There should at least be ' . $this->minTrackPointsPerSegment . ' trackpoints present!'
        );
    }
}
