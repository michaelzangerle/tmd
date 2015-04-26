<?php

namespace FHV\Bundle\TmdBundle\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;

use FHV\Bundle\TmdBundle\Entity\Trackpoint as TrackpointEntity;
use FHV\Bundle\TmdBundle\Entity\Tracksegment as SegmentEntity;

use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use FHV\Bundle\TmdBundle\Model\Trackpoint;
use FHV\Bundle\TmdBundle\Model\TracksegmentType;
use FHV\Bundle\TmdBundle\Util\TrackpointUtil;

class SegmentationFilter extends AbstractFilter
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

    function __construct(
        TrackpointInterface $util,
        $maxWalkVelocity,
        $maxWalkAcceleration,
        $minSegmentTime,
        $minSegmentDistance
    ) {
        $this->util = $util;
        $this->maxWalkVelocity = $maxWalkVelocity;
        $this->maxWalkAcceleration = $maxWalkAcceleration;
        $this->minSegmentTime = $minSegmentTime;
        $this->minSegmentDistance = $minSegmentDistance;
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

        // segmente mit bestimmter mindestlänge wird als sicheres segment betrachtet - ansonsten unsicheres segment
        // bestimmte anzahl von aufeinanderfolgenden unsicheren segementen werden in nicht geh segment gemerged
        // start und endpunkt eines geh segments sind hinweise auf tm wechsel

        $this->write($segments);
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

        $curSegment = new SegmentEntity();
        $trackPoint = $this->createTrackpointEntity($gpsSegment->getTrackPoints()[$i]);
        $curSegment->addTrackpoint($trackPoint);

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

            if (count($curSegment->getTrackpoints()) === 1 && $isWalkPoint) {
                // segment with one element und undefined type
                $curSegment->getResult()->setTransportType(TracksegmentType::WALK);
            } elseif ($this->newSegmentNeeded($isWalkPoint, $curSegment)) {
                // more than 1 element in segment and different type
                $segments[] = $curSegment;
                $curSegment->setTime($time);
                $curSegment->setDistance($distance);
                $curSegment = new SegmentEntity();
                $time = 0;
                $distance = 0;
            }

            $curSegment->addTrackpoint($tpEntity);
            $next++;
            $i++;
        }

        $segments[] = $curSegment;

        return $segments;
    }

    /**
     * Creates a trackpoint entity from a trackpoint model
     *
     * @param TrackpointInterface $tp
     *
     * @return TrackpointEntity
     */
    private function createTrackpointEntity(TrackpointInterface $tp)
    {
        $trackpoint = new TrackpointEntity();
        $trackpoint->setLong($tp->getLong());
        $trackpoint->setLat($tp->getLat());
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
     * @param boolean $isWalkPoint
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
     *
     * @param array $segments
     *
     * @return SegmentEntity[]
     */
    private function mergeSegments(array $segments)
    {
        $result = [];
        $i = -1;
        /** @var SegmentEntity $seg */
        foreach ($segments as $seg) {
            // TODO check default/configured values
            if ($seg->getTime() < $this->minSegmentTime || $seg->getDistance() < $this->minSegmentDistance) {
                $this->merge($result[$i - 1], $seg);
            } else {
                $result[] = $seg;
                $i++;
            }
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
        $seg1->setTime($seg1->getTime() + $seg2->getTime());
        $seg1->setDistance(($seg1->getDistance() + $seg2->getDistance()));
        $seg1->setEnd($seg2->getTrackpoints()->last());

        // TODO does this work?
        $newTrackpoints = new ArrayCollection(
            array_merge($seg1->getTrackpoints()->getValues(), $seg2->getTrackpoints()->getValues())
        );
        $seg1->setTrackpoints($newTrackpoints);
    }
}
