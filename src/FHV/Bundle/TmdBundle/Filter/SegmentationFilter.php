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

    function __construct(
        EntityManager $em,
        TrackpointUtilInterface $util,
        $maxWalkVelocity,
        $maxWalkAcceleration,
        $minSegmentTime,
        $minSegmentDistance
    ) {
        parent::__construct();

        $this->em = $em;
        $this->util = $util;
        $this->maxWalkVelocity = $maxWalkVelocity;
        $this->maxWalkAcceleration = $maxWalkAcceleration;
        $this->minSegmentTime = $minSegmentTime;
        $this->minSegmentDistance = $minSegmentDistance;
        $this->track = new TrackEntity();
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
        $segments = $this->mergeSegments($segments);
        $this->track->setSegments($segments);
        $this->write($this->track);
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

        $curSegment = $this->createNewSegmentEntity();
        $trackPoint = $this->createTrackpointEntity($gpsSegment->getTrackPoints()[$i]);
        $curSegment->addTrackpoint($trackPoint);
        $trackPoint->setSegment($curSegment);

        // TODO bilijecki?

        while ($next < $length) {
            /** @var TrackPoint $tp1 */
            /** @var TrackPoint $tp2 */
            $tp1 = $gpsSegment->getTrackPoints()[$i];
            $tp2 = $gpsSegment->getTrackPoints()[$next];
            $tmpDistance = $this->util->calcDistance($tp1, $tp2);
            $tmpTime = $this->util->calcTime($tp1, $tp2);
            $distance += $tmpDistance;
            $time += $tmpTime;

            $isWalkPoint = $this->isWalkPoint($tmpDistance, $tmpTime, $prevVelocity);
            $tpEntity = $this->createTrackpointEntity($tp2);

            if (count($curSegment->getTrackpoints()) === 1) {
                // segment with one element und undefined type
                $this->createNewResultEntity($this->track->getAnalyzationType(), $isWalkPoint, $curSegment);
            } elseif ($this->newSegmentNeeded($isWalkPoint, $curSegment)) {
                // more than 1 element in segment and different type
                $segments[] = $this->setValuesForSegment(
                    $curSegment,
                    $time,
                    $distance,
                    $curSegment->getTrackpoints()->first(),
                    $curSegment->getTrackpoints()->last()
                );

                $curSegment = $this->createNewSegmentEntity();
                $time = 0;
                $distance = 0;
            }

            $curSegment->addTrackpoint($tpEntity);
            $tpEntity->setSegment($curSegment);
            $next++;
            $i++;
        }

        $segments[] = $this->setValuesForSegment(
            $curSegment,
            $time,
            $distance,
            $curSegment->getTrackpoints()->first(),
            $curSegment->getTrackpoints()->last()
        );

        return $segments;
    }

    /**
     * Sets all needed values for a segment
     *
     * @param SegmentEntity    $segment
     * @param integer          $time
     * @param float            $distance
     * @param TrackpointEntity $first
     * @param TrackpointEntity $last
     *
     * @return SegmentEntity
     */
    private function setValuesForSegment($segment, $time, $distance, $first, $last)
    {
        $segment->setSeconds($time);
        $segment->setDistance($distance);
        $segment->setEnd($last);
        $segment->setStart($first);

        return $segment;
    }

    /**
     * Created a new segment entity, connects it with the tracks entity and persists it
     * @return SegmentEntity
     */
    private function createNewSegmentEntity()
    {
        $segment = new SegmentEntity();
        $segment->setTrack($this->track);
        $this->track->addSegment($segment);
        $this->em->persist($segment);

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

        $this->em->persist($result);

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

        $this->em->persist($trackpoint);

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

        // TODO check default/configured values
        if ($acc < $this->maxWalkAcceleration && $velocity < $this->maxWalkVelocity) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a new segment should be created because the types of the next
     * trackpoint and the current segment do not match
     *
     * @param boolean       $isWalkPoint
     * @param SegmentEntity $curSegment
     *
     * @return bool
     */
    private function newSegmentNeeded($isWalkPoint, SegmentEntity $curSegment)
    {
        if (($isWalkPoint &&
                $curSegment->getResult()->getTransportType() === TracksegmentType::UNDEFINIED) ||
            (!$isWalkPoint &&
                $curSegment->getResult()->getTransportType() === TracksegmentType::WALK)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Merges segments which are below a distance or time threshold
     * TODO merge first in second?
     *
     * @param SegmentEntity[] $segments
     *
     * @return SegmentEntity[]
     */
    private function mergeSegments(array $segments)
    {
        $result[] = $segments[0];
        $i = 1;
        $j = 0;

        while ($i < count($segments)) {
            // TODO check default/configured values
            if ($segments[$i]->getDistance() < $this->minSegmentDistance ||
                $segments[$i]->getSeconds() < $this->minSegmentTime
            ) {
                $this->merge($result[$j], $segments[$i]);
            } else {
                $result[] = $segments[$i];
                $j++;
            }
            $i++;
        }

        return $result;
    }

    /**
     * Merges the second segment into the first
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

        if($seg2->getResult()) {
            $this->em->detach($seg2->getResult());
        }

        $this->em->detach($seg2);
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
