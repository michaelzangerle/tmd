<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\TrackPoint;
use FHV\Bundle\TmdBundle\Model\TrackPointInterface;
use FHV\Bundle\TmdBundle\Util\TrackPointUtil;

/**
 * Filter and removes single trackpoints from an array of trackpoints
 * Class TrackPointFilter
 */
class TrackPointFilter extends AbstractFilter
{
    /**
     * @var int
     */
    private $minTimeDifference;

    /**
     * @var float
     */
    private $maxVelocity;
    /**
     * @var TrackPointUtil
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

    function __construct(
        $util,
        $maxDistance,
        $minDistance,
        $maxAltitudeChange,
        $minTimeDifference,
        $minTrackPointsPerSegment,
        $maxVelocity
    ) {
        parent::__construct();
        $this->util = $util;
        $this->maxDistance = $maxDistance;
        $this->minDistance = $minDistance;
        $this->maxAltitudeChange = $maxAltitudeChange;
        $this->minTimeDifference = $minTimeDifference;
        $this->minTrackPointsPerSegment = $minTrackPointsPerSegment;
        $this->maxVelocity = $maxVelocity;
    }

    /**
     * Checks if the distance between two trackpoints does make sense
     * @param TrackPointInterface $tp1
     * @param TrackPointInterface $tp2
     * @return bool
     */
    protected function isValidDistance(TrackPointInterface $tp1, TrackPointInterface $tp2)
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
     * @param TrackPointInterface $tp1
     * @param TrackPointInterface $tp2
     * @return bool
     */
    protected function isValidAltitudeChange(TrackPointInterface $tp1, TrackPointInterface $tp2)
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
     * @param TrackPointInterface $tp1
     * @param TrackPointInterface $tp2
     * @return bool
     */
    protected function isValidTime(TrackPointInterface $tp1, TrackPointInterface $tp2)
    {
        $time = $this->util->calcTime($tp2, $tp1);
        if ($time > 0 && $time >= $this->minTimeDifference) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the velocity value is valid
     * @param TrackPointInterface $tp1
     * @param TrackPointInterface $tp2
     * @return bool
     */
    private function isValidVelocity($tp1, $tp2)
    {
        $distance = $this->util->calcDistance($tp1, $tp2);
        $time = $this->util->calcTime($tp2, $tp1);
        $velocity = $this->util->calcVelocity($distance, $time);
        if($time > 0 && $velocity <= $this->maxVelocity){
            return true;
        }
        return false;
    }

    /**
     * Validates trackpoints and removes invalid ones
     * @param array $trackPoints
     * @return array
     */
    protected function filter(array $trackPoints)
    {
        $length = count($trackPoints);
        $i = 0;
        $next = 1;
        $cleaned = [];

        // TODO special find first valid point check?

        while ($next < $length) {
            $tp1 = new TrackPoint($trackPoints[$i]);
            $tp2 = new TrackPoint($trackPoints[$next]);

            if ($this->isValidTime($tp2, $tp1) &&
                $this->isValidDistance($tp2, $tp1) &&
                $this->isValidAltitudeChange($tp1, $tp2) &&
                $this->isValidVelocity($tp1, $tp2)
            ) {
                $cleaned[] = $trackPoints[$i];
                $i = $next;
            }

            $next++;
        }

        // TODO special last point check?

        return $cleaned;
    }

    /**
     * Starts a filter and processes the given data
     * @param $data
     * @throws FilterException
     */
    public function run($data)
    {
        if (isset($data['trackPoints'])) {
            $data['trackPoints'] = $this->filter($data['trackPoints']);
            if (count($data['trackPoints']) >= $this->minTrackPointsPerSegment) {
                $this->write($data);
            }
        } else {
            throw new InvalidArgumentException('TrackPointFilter: Data param should contain trackpoints!');
        }
    }
}
