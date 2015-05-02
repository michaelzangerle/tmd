<?php

namespace FHV\Bundle\TmdBundle\Filter;

use Doctrine\ORM\EntityManager;
use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;

use FHV\Bundle\TmdBundle\Entity\Track as TrackEntity;
use FHV\Bundle\TmdBundle\Entity\Result as ResultEntity;
use FHV\Bundle\TmdBundle\Entity\Trackpoint as TrackpointEntity;
use FHV\Bundle\TmdBundle\Entity\Tracksegment as SegmentEntity;

use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use FHV\Bundle\TmdBundle\Model\TracksegmentType;

use FHV\Bundle\TmdBundle\Util\TrackpointUtil;
use FHV\Bundle\TmdBundle\Util\TrackpointUtilInterface;

/**
 * Class SegmentationFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class SegmentationFilter extends AbstractFilter implements SegmentationFilterInterface
{
    /**
     * @var TrackpointUtil
     */
    private $util;

    /**
     * @var float
     */
    private $maxWalkVelocity;

    /**
     * @var float
     */
    private $maxWalkAcceleration;

    /**
     * @var float
     */
    private $minSegmentDistance;

    /**
     * @var int
     */
    private $minSegmentTime;

    /**
     * @var TrackEntity
     */
    private $track;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var integer
     */
    private $maxTimeDifference;

    /**
     * @var integer
     */
    private $maxTimeWithoutMovement;

    /**
     * @var float
     */
    private $maxVelocityForNearlyStopPoints;

    function __construct(
        EntityManager $em,
        TrackpointUtilInterface $util,
        $maxWalkVelocity,
        $maxWalkAcceleration,
        $minSegmentTime,
        $minSegmentDistance,
        $maxTimeDifference,
        $maxTimeWithoutMovement,
        $maxVelocityForNearlyStopPoints
    ) {
        parent::__construct();

        $this->em = $em;
        $this->util = $util;
        $this->maxWalkVelocity = $maxWalkVelocity;
        $this->maxWalkAcceleration = $maxWalkAcceleration;
        $this->minSegmentTime = $minSegmentTime;
        $this->minSegmentDistance = $minSegmentDistance;
        $this->track = new TrackEntity();
        $this->maxTimeDifference = $maxTimeDifference;
        $this->maxTimeWithoutMovement = $maxTimeWithoutMovement;
        $this->maxVelocityForNearlyStopPoints = $maxVelocityForNearlyStopPoints;
    }

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws FilterException
     */
    public function run($data)
    {
        if ($data !== null && $data instanceof TracksegmentInterface) {
            $this->process($data);
            if ($this->getParentHasFinished()) {
                $this->write($this->track);
                $this->finished();
            }
        } else {
            throw new FilterException('Invalid data. Data must implement SegmentInterface!');
        }
    }

    /**
     * Segments the gps segment into single transportation mode segments
     *
     * @param TracksegmentInterface $gpsSegment
     */
    private function process(TracksegmentInterface $gpsSegment)
    {
        $segments = $this->createSegments($gpsSegment);
        $this->mergeSegments($segments);
    }

    /**
     * Creates basic segments from gps segment
     *
     * @param TracksegmentInterface $gpsSegment
     *
     * @return SegmentEntity[]
     */
    private function createSegments(TracksegmentInterface $gpsSegment)
    {
        $segments = [];
        $i = 0;
        $next = 1;
        $length = count($gpsSegment->getTrackPoints());
        $prevVelocity = 0;
        $time = 0;
        $distance = 0;
        $lowSpeedTimeCounter = 0;

        $curSegment = $this->createNewSegmentEntity();
        $trackPoint = $this->createTrackpointEntity($gpsSegment->getTrackPoints()[$i]);
        $curSegment->addTrackpoint($trackPoint);
        $trackPoint->setSegment($curSegment);

        while ($next < $length) {
            /** @var TrackPoint $tp1 */
            /** @var TrackPoint $tp2 */
            $tp1 = $gpsSegment->getTrackPoints()[$i];
            $tp2 = $gpsSegment->getTrackPoints()[$next];
            $tmpDistance = $this->util->calcDistance($tp1, $tp2);
            $tmpTime = $this->util->calcTime($tp1, $tp2);
            $distance += $tmpDistance;
            $time += $tmpTime;

            // bilijecki counts the points with speed below a certain value
            // and starts a new segment when the threshold is reached
            if($this->util->calcVelocity($tmpDistance, $tmpTime) < $this->maxVelocityForNearlyStopPoints) {
                $lowSpeedTimeCounter += $tmpTime;
            }

            $isWalkPoint = $this->isWalkPoint($tmpDistance, $tmpTime, $prevVelocity);
            $tpEntity = $this->createTrackpointEntity($tp2);

            // segment with one element und undefined type
            if (count($curSegment->getTrackpoints()) === 1) {
                $this->createNewResultEntity($this->track->getAnalyzationType(), $isWalkPoint, $curSegment);
            } elseif ($this->newSegmentNeeded($isWalkPoint, $curSegment, $tmpTime, $lowSpeedTimeCounter)) {
                // more than 1 element in segment and different type
                $segments[] = $this->setValuesForSegment(
                    $curSegment,
                    $time,
                    $distance
                );

                $curSegment = $this->createNewSegmentEntity();
                $time = 0;
                $distance = 0;
                $lowSpeedTimeCounter = 0;
            }

            $curSegment->addTrackpoint($tpEntity);
            $tpEntity->setSegment($curSegment);
            $next++;
            $i++;
        }

        $segments[] = $this->setValuesForSegment(
            $curSegment,
            $time,
            $distance
        );

        return $segments;
    }

    /**
     * Sets all needed values for a segment
     *
     * @param SegmentEntity $segment
     * @param integer       $time
     * @param float         $distance
     *
     * @return SegmentEntity
     */
    private function setValuesForSegment($segment, $time, $distance)
    {
        $segment->setSeconds($time);
        $segment->setDistance($distance);
        $segment->setEnd($segment->getTrackpoints()->last());
        $segment->setStart($segment->getTrackpoints()->first());

        return $segment;
    }

    /**
     * Created a new segment entity but do not connect it with the track because
     * this will happen during the merge process
     * @return SegmentEntity
     */
    private function createNewSegmentEntity()
    {
        $segment = new SegmentEntity();

        return $segment;
    }

    /**
     * Creates a new result entity and sets the relation to the segment and persists it
     *
     * @param integer       $getAnalyzationType
     * @param boolean       $isWalkPoint
     * @param SegmentEntity $curSegment
     *
     * @return ResultEntity
     */
    private function createNewResultEntity($getAnalyzationType, $isWalkPoint, $curSegment)
    {
        $result = new ResultEntity();
        $result->setAnalizationType($getAnalyzationType);
        $result->setSegment($curSegment);
        $curSegment->setResult($result);

        if ($isWalkPoint) {
            $result->setTransportType(TracksegmentType::WALK);
        } else {
            $result->setTransportType(TracksegmentType::UNDEFINIED);
        }

        return $result;
    }

    /**
     * Creates a trackpoint entity from a trackpoint model and persits it
     *
     * @param TrackpointInterface $tp
     *
     * @return TrackpointEntity
     */
    private function createTrackpointEntity(TrackpointInterface $tp)
    {
        $trackpoint = new TrackpointEntity();
        $trackpoint->setLongitude($tp->getLong());
        $trackpoint->setLatitude($tp->getLat());
        $trackpoint->setTime($tp->getTime());

        return $trackpoint;
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
    private function isWalkPoint($distance, $time, &$prevVelocity)
    {
        $velocity = $this->util->calcVelocity($distance, $time);
        $acc = $this->util->calcAcceleration($velocity, $time, $prevVelocity);
        $prevVelocity = $velocity;

        if ($acc < $this->maxWalkAcceleration && $velocity < $this->maxWalkVelocity) {
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
     * @param boolean       $isWalkPoint
     * @param SegmentEntity $curSegment
     * @param integer       $time
     * @param integer       $lowSpeedCounter
     *
     * @return bool
     */
    private function newSegmentNeeded($isWalkPoint, SegmentEntity $curSegment, $time, $lowSpeedCounter)
    {
        // TODO bilijecki?
        if (($isWalkPoint && $curSegment->getResult()->getTransportType() === TracksegmentType::UNDEFINIED) ||
            (!$isWalkPoint && $curSegment->getResult()->getTransportType() === TracksegmentType::WALK) ||
            ($time > $this->maxTimeDifference) ||
            $lowSpeedCounter >= $this->maxTimeWithoutMovement
        ) {
            return true;
        }

        return false;
    }

    /**
     * Merges segments which are below a distance or time threshold
     *
     * @param SegmentEntity[] $segments
     *
     * @return SegmentEntity[]
     */
    private function mergeSegments(array $segments)
    {
        $i = 1;
        $j = 0;
        $amount = count($segments);

        // merge first segment with second when too small
        // to prevent starting signal issue
        if (count($segments) > 1 && $this->shouldSegementBeMerged($segments[0])) {
            $this->merge($segments[1], $segments[0]);
            $curSegment = $segments[1];
            $i = 2;
        } else {
            $curSegment = $segments[0];
        }

        $curSegment->setTrack($this->track);
        $this->track->addSegment($curSegment);

        while ($i < $amount) {
            if ($this->shouldSegementBeMerged($segments[$i])) {
                $this->merge($curSegment, $segments[$i]);
            } else {
                // add segment only when it should not be merged
                $segments[$i]->setTrack($this->track);
                $this->track->addSegment($segments[$i]);
                $curSegment = $segments[$i];
                $j++;
            }
            $i++;
        }
    }

    /**
     * Determines if a segment should be merged with another or not
     *
     * @param SegmentEntity $segment
     *
     * @return bool
     */
    private function shouldSegementBeMerged(SegmentEntity $segment)
    {
        return $segment->getDistance() < $this->minSegmentDistance ||
        $segment->getSeconds() < $this->minSegmentTime;
    }

    /**
     * Merges the second segment into the first and detaches the second
     *
     * @param SegmentEntity $seg1
     * @param SegmentEntity $seg2
     */
    private function merge(SegmentEntity $seg1, SegmentEntity $seg2)
    {
        $seg1->setSeconds($seg1->getSeconds() + $seg2->getSeconds());
        $seg1->setDistance(($seg1->getDistance() + $seg2->getDistance()));
        $seg1->setEnd($seg2->getTrackpoints()->last());

        /** @var TrackpointEntity $tp */
        foreach ($seg2->getTrackpoints() as $tp) {
            $seg1->addTrackpoint($tp);
            $tp->setSegment($seg1);
        }
    }

    /**
     * @return TrackEntity
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * @param TrackEntity $track
     */
    public function setTrack(TrackEntity $track)
    {
        $this->track = $track;
    }
}
