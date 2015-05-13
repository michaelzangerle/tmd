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
     * {@inheritdoc}
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * {@inheritdoc}
     */
    public function setSegments(array $segments)
    {
        $this->segments = array_merge($this->segments, $segments);
    }

    /**
     * {@inheritdoc}
     */
    public function getAnalysisType()
    {
        return $this->analysisType;
    }

    /**
     * {@inheritdoc}
     */
    public function setAnalysisType($analysisType)
    {
        $this->analysisType = $analysisType;
    }

    /**
     * {@inheritdoc}
     */
    public function addSegment(TracksegmentInterface $segment)
    {
        $this->segments[] = $segment;
    }
}
