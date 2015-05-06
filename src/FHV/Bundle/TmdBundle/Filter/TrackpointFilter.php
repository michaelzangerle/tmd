<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\TmdBundle\Exception\TrackException;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Util\TrackpointUtilInterface;

/**
 * Filter and removes single trackpoints from an array of trackpoints
 * Class TrackPointFilter
 */
class TrackpointFilter extends AbstractFilter
{
    /**
     * @var int
     */
    private $minTimeDifference;

    /**
     * @var float percentage value (valid points in relation to all points in the track)
     */
    private $minValidPointsRatio;

    /**
     * @var TrackpointUtilInterface
     */
    private $util;

    /**
     * @var float
     */
    private $maxDistance;

    /**
     * @var float
     */
    private $minDistance;

    /**
     * @var float
     */
    private $maxAltitudeChange;

    /**
     * @var integer
     */
    private $pointsToSkip;

    /**
     * @var int
     */
    private $validPointCounter = 0;

    function __construct(
        $util,
        $maxDistance,
        $minDistance,
        $maxAltitudeChange,
        $minTimeDifference,
        $minTrackPointsPerSegment,
        $pointsToSkip,
        $minValidTime
    ) {
        parent::__construct();
        $this->util = $util;
        $this->maxDistance = $maxDistance;
        $this->minDistance = $minDistance;
        $this->maxAltitudeChange = $maxAltitudeChange;
        $this->minTimeDifference = $minTimeDifference;
        $this->minTrackPointsPerSegment = $minTrackPointsPerSegment;
        $this->minValidPointsRatio = $minValidTime;
        $this->pointsToSkip = $pointsToSkip;
    }

    /**
     * Checks if the distance between two trackpoints does make sense
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return bool
     */
    protected function isValidDistance(TrackpointInterface $tp1, TrackpointInterface $tp2)
    {
        if ($tp1 !== null && $tp2 !== null) {
            $time = $this->util->calcTime($tp2, $tp1);
            $time = $time > 0 ? $time : 1; // should not happen
            $distance = $this->util->calcDistance($tp1, $tp2);
            if ($distance > ($this->minDistance * $time) && $distance < ($this->maxDistance * $time)) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Checks if the change of the elevation value is valid
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return bool
     */
    protected function isValidAltitudeChange(TrackpointInterface $tp1, TrackpointInterface $tp2)
    {
        $elevation = $this->util->calcElevation($tp1, $tp2);
        $time = $this->util->calcTime($tp2, $tp1);
        if ($time > 0 && ($elevation / $time) < $this->maxAltitudeChange) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the change of the time value is valid
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return bool
     */
    protected function isValidTime(TrackpointInterface $tp1, TrackpointInterface $tp2)
    {
        $time = $this->util->calcTime($tp2, $tp1);
        if ($time > 0 && $time >= $this->minTimeDifference) {
            return true;
        }

        return false;
    }

    /**
     * Validates trackpoints and removes invalid ones
     *
     * @param array $trackPoints
     *
     * @return array
     * @throws TrackException
     */
    protected function filter(array $trackPoints)
    {
        $length = count($trackPoints);
        $i = $this->pointsToSkip;
        $next = $i + 1;
        $cleaned = [];

        if ($i > -1) { // valid start point found
            $this->validPointCounter++; // first point

            while ($next < $length) {
                $tp1 = new Trackpoint($trackPoints[$i]);
                $tp2 = new Trackpoint($trackPoints[$next]);

                if ($this->areTrackpointsValid($tp1, $tp2)) {
                    $this->validPointCounter++;
                    $cleaned[] = $trackPoints[$i];
                    $i = $next;
                }

                $next++;
            }

            return $cleaned;
        }

        throw new TrackException('TrackPointFilter: No valid starting point found!');
    }

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws TrackException
     */
    public function run($data)
    {
        if (isset($data['trackPoints'])) {
            // before being processed
            $totalAmountOfTrackPoints = count($data['trackPoints']);
            $data['trackPoints'] = $this->filter($data['trackPoints']);
            $validPointThreshold = $this->validPointCounter / $totalAmountOfTrackPoints;

            if (count($data['trackPoints']) >= $this->minTrackPointsPerSegment &&
                $validPointThreshold >= $this->minValidPointsRatio
            ) {
                $this->write($data);
                if ($this->getParentHasFinished()) {
                    $this->finished();
                }
            }
            // else just skip this segment
        } else {
            throw new TrackException('TrackPointFilter: Data param should contain trackpoints!');
        }
    }

    /**
     * Checks if a trackpoint-pair is valid
     *
     * @param TrackpointInterface $tp1
     * @param TrackpointInterface $tp2
     *
     * @return bool
     */
    protected function areTrackpointsValid($tp1, $tp2)
    {
        // time filter has to be used first to be sure time value > 0
        return $this->isValidTime($tp2, $tp1) &&
        $this->isValidDistance($tp2, $tp1) &&
        $this->isValidAltitudeChange($tp1, $tp2);
    }
}
