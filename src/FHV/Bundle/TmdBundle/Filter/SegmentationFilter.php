<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;

use FHV\Bundle\TmdBundle\Model\Result;
use FHV\Bundle\TmdBundle\Model\ResultInterface;
use FHV\Bundle\TmdBundle\Model\Track;
use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use FHV\Bundle\TmdBundle\Model\Tracksegment;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use FHV\Bundle\TmdBundle\Model\TracksegmentType;

use FHV\Bundle\TmdBundle\Util\TrackpointUtil;
use FHV\Bundle\TmdBundle\Util\TrackpointUtilInterface;

/**
 * Segments a given track into parts with only one transport mode
 * Class SegmentationFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class SegmentationFilter extends AbstractComponent
{
    /**
     * @var TrackpointUtil
     */
    protected $util;

    /**
     * Maximum velocity for walk points
     * @var float
     */
    protected $walkpointMaxVelocity;

    /**
     * Maximum acceleration for walk points
     * @var float
     */
    protected $walkpointMaxAcceleration;

    /**
     * Minimal distance for a segment to cover
     * @var float
     */
    protected $segmentMinDistance;

    /**
     * Minimal time for a segment to cover
     * @var int
     */
    protected $segmentMinTime;

    /**
     * @var Track
     */
    protected $track;

    /**
     * Maximum time difference between two consecutive track points
     * @var integer
     */
    protected $trackpointMaxTimeDifference;

    /**
     * Minimum time of points to cover to detect as stop point
     * @var integer
     */
    protected $stoppointMinTimeWithoutMovement;

    /**
     * Maximum velocity for stop points
     * @var float
     */
    protected $stoppointMaxVelocity;

    /**
     * Analyse type (basic, gis)
     * @var string
     */
    protected $analyseType;

    /**
     * Minimum time to cover to become a certain segment
     * @var float
     */
    protected $certainSegmentMinTime;

    /**
     * Minimum distance to cover to become a certain segment
     * @var float
     */
    protected $certainSegmentMinDistance;

    /**
     * Type of transport read from the file
     * @var string
     */
    protected $segmentType;

    function __construct(
        TrackpointUtilInterface $util,
        $walkpointMaxVelocity,
        $walkpointMaxAcceleration,
        $segmentMinTime,
        $segmentMinDistance,
        $trackpointMaxTimeDifference,
        $stoppointTimeWithoutMovement,
        $stoppointMaxVelocity,
        $certainSegmentMinTime,
        $certainSegmentMinDistance
    ) {
        parent::__construct();
        $this->util = $util;
        $this->track = new Track();

        $this->walkpointMaxVelocity = $walkpointMaxVelocity;
        $this->walkpointMaxAcceleration = $walkpointMaxAcceleration;

        $this->segmentMinTime = $segmentMinTime;
        $this->segmentMinDistance = $segmentMinDistance;
        $this->trackpointMaxTimeDifference = $trackpointMaxTimeDifference;

        $this->stoppointMinTimeWithoutMovement = $stoppointTimeWithoutMovement;
        $this->stoppointMaxVelocity = $stoppointMaxVelocity;

        $this->certainSegmentMinTime = $certainSegmentMinTime;
        $this->certainSegmentMinDistance = $certainSegmentMinDistance;
    }

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws ComponentException
     */
    public function run($data)
    {
        if ($data !== null &&
            array_key_exists('trackPoints', $data) && $data['trackPoints'] !== null &&
            array_key_exists('analyseType', $data) && $data['analyseType'] !== null
        ) {
            $this->analyseType = $data['analyseType'];
            $this->segmentType = $data['type'];
            $this->process($data['trackPoints']);
        } else {
            throw new ComponentException('Invalid data. Data must contain trackpoints and analyse type!');
        }
    }

    /**
     * Put the trackpoints into segments
     *
     * @param array $trackpoints
     */
    protected function process(array $trackpoints)
    {
        $segments = $this->createSegments($trackpoints);
        $segments = $this->mergeShortSegments($segments);
        $segments = $this->mergeUncertainSegments($segments);
        $this->track->setSegments($segments);
        $this->track->setAnalysisType($this->analyseType);
    }

    /**
     * Creates basic segments from gps segment
     *
     * @param TrackpointInterface[] $trackpoints
     *
     * @return TracksegmentInterface[]
     */
    protected function createSegments(array $trackpoints)
    {
        $segments = [];
        $i = 0;
        $next = 1;
        $length = count($trackpoints);
        $prevVelocity = 0;
        $time = 0;
        $distance = 0;
        $tmpTime = 0;
        $lowSpeedTimeCounter = 0;

        $curSegment = $this->createNewSegment();
        $curSegment->addTrackpoint($trackpoints[$i]);

        while ($next < $length) {
            /** @var TrackPoint $tp1 */
            /** @var TrackPoint $tp2 */
            $tp1 = $trackpoints[$i];
            $tp2 = $trackpoints[$next];
            $tmpDistance = $this->util->calcDistance($tp1, $tp2);
            $tmpTime = $this->util->calcTime($tp1, $tp2);
            $distance += $tmpDistance;
            $time += $tmpTime;

            // bilijecki counts the points with speed below a certain value
            // and starts a new segment when the threshold is reached
            if ($this->util->calcVelocity($tmpDistance, $tmpTime) < $this->stoppointMaxVelocity) {
                $lowSpeedTimeCounter += $tmpTime;
            } else {
                // reset when below threshold
                $lowSpeedTimeCounter = 0;
            }

            $isWalkPoint = $this->isWalkPoint($tmpDistance, $tmpTime, $prevVelocity);

            // segment with one element und undefined type
            if (count($curSegment->getTrackpoints()) === 1) {
                $this->createNewResultEntity($this->analyseType, $isWalkPoint, $curSegment);
            } elseif ($this->newSegmentNeeded($isWalkPoint, $curSegment, $tmpTime, $lowSpeedTimeCounter)) {
                // more than 1 element in segment and different type
                $segments[] = $this->setValuesForSegment(
                    $curSegment,
                    $time,
                    $distance,
                    $tmpTime
                );

                $curSegment = $this->createNewSegment();
                $time = 0;
                $distance = 0;
                $lowSpeedTimeCounter = 0;
            }

            $curSegment->addTrackpoint($tp2);
            $next++;
            $i++;
        }

        $segments[] = $this->setValuesForSegment(
            $curSegment,
            $time,
            $distance,
            $tmpTime
        );

        return $segments;
    }

    /**
     * Sets all needed values for a segment
     *
     * @param TracksegmentInterface $segment
     * @param integer $time
     * @param float $distance
     * @param integer $tmpTime
     *
     * @return TracksegmentInterface
     */
    protected function setValuesForSegment($segment, $time, $distance, $tmpTime)
    {
        $segment->setTime($time);
        $segment->setDistance($distance);
        $length = count($segment->getTrackPoints());
        $segment->setEndPoint($segment->getTrackpoints()[$length - 1]);
        $segment->setStartPoint($segment->getTrackpoints()[0]);

        if($this->segmentType) {
            $segment->setType($this->segmentType); // when type is defined assume this is the correct one and set it
        }

        if ($tmpTime > $this->trackpointMaxTimeDifference) {
            $segment->setCertainSegment(true);
        }

        return $segment;
    }

    /**
     * Creates a new plain segment
     * this will happen during the merge process
     * @return TracksegmentInterface
     */
    protected function createNewSegment()
    {
        $segment = new Tracksegment();

        return $segment;
    }

    /**
     * Creates a new result entity and sets the relation to the segment and persists it
     *
     * @param integer $getAnalyseType
     * @param boolean $isWalkPoint
     * @param TracksegmentInterface $curSegment
     *
     * @return ResultInterface
     */
    protected function createNewResultEntity($getAnalyseType, $isWalkPoint, $curSegment)
    {
        $result = new Result();
        $result->setAnalisationType($getAnalyseType);
        $curSegment->setResult($result);

        if ($isWalkPoint) {
            $result->setTransportType(TracksegmentType::WALK);
            $curSegment->setCertainSegment(true);
        } else {
            $result->setTransportType(TracksegmentType::UNDEFINED);
        }

        $curSegment->setType($result->getTransportType());

        return $result;
    }

    /**
     * Checks if second trackpoint is a possible walk point
     * Calculates velocity and acceleration to determine type
     *
     * @param $distance float
     * @param $time integer
     * @param $prevVelocity
     *
     * @return bool
     */
    protected function isWalkPoint($distance, $time, &$prevVelocity)
    {
        $velocity = $this->util->calcVelocity($distance, $time);
        $acc = $this->util->calcAcceleration($velocity, $time, $prevVelocity);
        $prevVelocity = $velocity;

        if ($acc < $this->walkpointMaxAcceleration && $velocity < $this->walkpointMaxVelocity) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a new segment should be created because the types of the next
     * trackpoint and the current segment do not match or because there is big
     * time difference between the current track points or because a lot of
     * segments without or with little movement were discovered
     *
     * @param boolean $isWalkPoint
     * @param TracksegmentInterface $curSegment
     * @param integer $trackPointTime
     * @param integer $lowSpeedCounter
     *
     * @return bool
     */
    protected function newSegmentNeeded(
        $isWalkPoint,
        TracksegmentInterface $curSegment,
        $trackPointTime,
        $lowSpeedCounter
    ) {
        if (count($curSegment->getTrackpoints()) >= 2 && (
                ($isWalkPoint && $curSegment->getResult()->getTransportType() === TracksegmentType::UNDEFINED) ||
                (!$isWalkPoint && $curSegment->getResult()->getTransportType() === TracksegmentType::WALK) ||
                ($trackPointTime > $this->trackpointMaxTimeDifference) || // no trackpoint for 30 seconds
                ($lowSpeedCounter >= $this->stoppointMinTimeWithoutMovement)) // stop detected
        ) {
            return true;
        }

        return false;
    }

    /**
     * Merges segments which are below a distance or time threshold
     *
     * @param TracksegmentInterface[] $segments
     *
     * @return TracksegmentInterface[]
     */
    protected function mergeShortSegments(array $segments)
    {
        $i = 1;
        $j = 0;
        $amount = count($segments);
        $result = [];

        // merge first segment with second when too small
        // to prevent starting signal issue
        if (count($segments) > 1 && $this->shouldShortSegmentBeMerged($segments[0])) {
            $this->merge($segments[0], $segments[1]);
            $i = 2;
        }
        $curSegment = $segments[0];
        $result[] = $curSegment;

        while ($i < $amount) {
            if ($this->shouldShortSegmentBeMerged($segments[$i])) {
                $this->merge($curSegment, $segments[$i]);
            } else {
                // add segment only when it should not be merged
                $result[] = $segments[$i];
                $curSegment = $segments[$i];
                $j++;
            }
            $i++;
        }

        return $result;
    }

    /**
     * Determines if a segment should be merged with another or not
     *
     * @param TracksegmentInterface $segment
     *
     * @return bool
     */
    protected function shouldShortSegmentBeMerged(TracksegmentInterface $segment)
    {
        return ($segment->getDistance() < $this->segmentMinDistance ||
            $segment->getTime() < $this->segmentMinTime);
    }

    /**
     * Merges the second segment into the first
     *
     * @param TracksegmentInterface $seg1
     * @param TracksegmentInterface $seg2
     */
    protected function merge(TracksegmentInterface $seg1, TracksegmentInterface $seg2)
    {
        $seg1->setTime($seg1->getTime() + $seg2->getTime());
        $seg1->setDistance(($seg1->getDistance() + $seg2->getDistance()));
        $length = count($seg2->getTrackpoints());
        $seg1->setEndPoint($seg2->getTrackpoints()[$length - 1]);

        /** @var TrackpointInterface $tp */
        foreach ($seg2->getTrackpoints() as $tp) {
            $seg1->addTrackpoint($tp);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parentHasFinished()
    {
        $this->write(
            [
                'track' => $this->track,
                'analyseType' => $this->analyseType,
            ]
        );
        parent::parentHasFinished();
        $this->track = new Track(); // for processing multiple files
    }

    /**
     * Merges uncertain segments which means segments that are non walk segments and
     * are below a certain length and time threshold
     *
     * @param TracksegmentInterface[] $segments
     *
     * @return TracksegmentInterface[]
     */
    protected function mergeUncertainSegments(array $segments)
    {
        $length = count($segments);
        if ($length <= 1) {
            return $segments;
        }

        $result[] = $segments[0];
        $last = 0;
        for ($i = 1; $i < $length; $i++) {
            if ($this->shouldUncertainSegmentBeMerged($result[$last], $segments[$i])) {
                $this->merge($result[$last], $segments[$i]);
            } else {
                $last++;
                $result[] = $segments[$i];
            }
        }

        return $result;
    }

    /**
     * Determines if two uncertain segments should be merged
     *
     * @param TracksegmentInterface $segment1
     * @param TracksegmentInterface $segment2
     *
     * @return bool
     */
    protected function shouldUncertainSegmentBeMerged(TracksegmentInterface $segment1, TracksegmentInterface $segment2)
    {
        if (!$segment1->isCertainSegment() && !$segment2->isCertainSegment() &&
            ($segment1->getDistance() < $this->certainSegmentMinDistance ||
                $segment1->getTime() < $this->certainSegmentMinTime)
        ) {
            return true;
        }

        return false;
    }
}
