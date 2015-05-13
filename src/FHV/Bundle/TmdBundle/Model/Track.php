<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Class Track
 */
class Track implements TrackInterface
{
    /**
     * @var TracksegmentInterface[]
     */
    protected $segments = [];

    /**
     * @var int
     */
    protected $analysisType = 0;

    /**
     * @return mixed
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * @param TracksegmentInterface[] $segments
     */
    public function setSegments(array $segments)
    {
        $this->segments = array_merge($this->segments, $segments);
    }

    /**
     * @return mixed
     */
    public function getAnalysisType()
    {
        return $this->analysisType;
    }

    /**
     * @param mixed $analysisType
     */
    public function setAnalysisType($analysisType)
    {
        $this->analysisType = $analysisType;
    }

    /**
     * Adds a track segment to the track
     *
     * @param TracksegmentInterface $segment
     */
    public function addSegment(TracksegmentInterface $segment)
    {
        $this->segments[] = $segment;
    }
}
