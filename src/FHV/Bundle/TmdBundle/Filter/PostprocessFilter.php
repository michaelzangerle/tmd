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

            if ($curSegment->getType() === TracksegmentType::BIKE &&
                $curSegment->getResult()->getProbability() < 0.7 // TODO put in config
            ) {
                if ($i > 1 && $segments[$i - 1]->getType() !== TracksegmentType::WALK) {
                    $this->adjustTransportMode($segments[$i - 1], $curSegment);
                } else {
                    $this->adjustTransportMode($nextSegment, $curSegment);
                }
            }

            if ($curSegment->getType() !== $nextSegment->getType() &&
                $curSegment->getType() !== TracksegmentType::WALK &&
                $nextSegment->getType() !== TracksegmentType::WALK
            ) {
                $this->adjustTransportMode($curSegment, $nextSegment);
            }
        }
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
}
