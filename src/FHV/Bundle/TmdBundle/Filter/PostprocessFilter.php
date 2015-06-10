<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\Result;
use FHV\Bundle\TmdBundle\Model\Track;
use FHV\Bundle\TmdBundle\Model\Tracksegment;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use FHV\Bundle\TmdBundle\Model\TracksegmentType;

/**
 * Determines if the transport modes make sense and changes them if not
 * Class PostprocessFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class PostprocessFilter extends AbstractComponent
{
    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws ComponentException
     */
    public function run($data)
    {
        if ($data !== null && $data instanceof Track) {
            $this->process($data);
            $this->write($data);
        } else {
            throw new InvalidArgumentException(
                'DatabaseFilter: Data param should be instance of Track!'
            );
        }
    }

    /**
     * Triggers the last analyse step in which changes in transportation modes will
     * be validated
     *
     * @param Track $track
     */
    protected function process(Track $track)
    {
        // biljecki: stops at traffic lights could cause errors in connection with bus stops
        // therefore it will be checked if previous was car - then car is set for this
        // zheng: change in transportation mode only with walk segment
        $segments = $track->getSegments();
        $length = count($segments);

        for ($i = 0; ($i + 1) < $length; $i++) {
            $curSegment = $segments[$i];
            $nextSegment = $segments[$i + 1];
            $previousSegment = $i > 0 ? $segments[$i - 1] : null;

            $this->mergeUncertainBikeMode($curSegment, $nextSegment, $previousSegment);
            $this->mergeFastTransportMode($curSegment, $nextSegment);
        }
    }

    /**
     * Checks if the given segment is associated with a fast transport type
     *
     * @param TracksegmentInterface $segment
     *
     * @return bool
     */
    protected function isFastTransportType(TracksegmentInterface $segment)
    {
        return ($segment->getType() === TracksegmentType::BUS ||
            $segment->getType() === TracksegmentType::DRIVE ||
            $segment->getType() === TracksegmentType::TRAIN);
    }

    /**
     * Adjusts the transport type/mode from the segment to correct according to the correct segment
     * and will set the result probability to -1 because the type was set in the postprocessing
     *
     * @param TracksegmentInterface $correctSegment
     * @param TracksegmentInterface $segmentToCorrect
     */
    protected function adjustTransportMode(
        TracksegmentInterface $correctSegment,
        TracksegmentInterface $segmentToCorrect
    ) {
        $segmentToCorrect->setType($correctSegment->getType());
        $segmentToCorrect->getResult()->setTransportType($correctSegment->getType());
        $segmentToCorrect->getResult()->setProbability(-1);
    }

    /**
     * Decides if given segments should be merged or not
     *
     * @param TracksegmentInterface $curSegment
     * @param TracksegmentInterface $nextSegment
     */
    protected function mergeFastTransportMode(TracksegmentInterface $curSegment, TracksegmentInterface $nextSegment)
    {
        if ($curSegment->getType() !== $nextSegment->getType() &&
            $curSegment->getType() !== TracksegmentType::WALK &&
            $nextSegment->getType() !== TracksegmentType::WALK
            // TODO needed? will break all where 1 bike segment is present
//            $this->isFastTransportType($curSegment) &&
//            $this->isFastTransportType($nextSegment)
        ) {
            $this->adjustTransportMode($curSegment, $nextSegment);
        }
    }

    /**
     * Merges an uncertain bike mode with the previous or next segment
     *
     * @param TracksegmentInterface $curSegment
     * @param TracksegmentInterface $nextSegment
     * @param TracksegmentInterface $previousSegment
     *
     */
    protected function mergeUncertainBikeMode(
        TracksegmentInterface $curSegment,
        TracksegmentInterface $nextSegment,
        TracksegmentInterface $previousSegment = null
    ) {
        if ($curSegment->getType() === TracksegmentType::BIKE && (
                (!$previousSegment || $previousSegment->getType() !== TracksegmentType::BIKE) &&
                $nextSegment->getType() !== TracksegmentType::BIKE)
        ) {
            if ($previousSegment && $previousSegment->getType() !== TracksegmentType::WALK) {
                $this->adjustTransportMode($previousSegment, $curSegment);
            } else {
                $this->adjustTransportMode($nextSegment, $curSegment);
            }
        }
    }
}
