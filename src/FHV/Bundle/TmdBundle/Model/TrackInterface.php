<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Interface TrackInterface
 * @package FHV\Bundle\TmdBundle\Model
 */
interface TrackInterface
{
    /**
     * Returns the type of analysis that is used for this track
     * @return int
     */
    public function getAnalysisType();

    /**
     * Sets the type of analysis that is used for this track
     *
     * @param int $type
     */
    public function setAnalysisType($type);

    /**
     * Returns all segments of the track
     * @return TrackSegmentInterface[]
     */
    public function getSegments();

    /**
     * Adds a track segment to the track
     *
     * @param TracksegmentInterface $segment
     */
    public function addSegment(TracksegmentInterface $segment);

    /**
     * Sets an array of segments for a track
     *
     * @param TracksegmentInterface[] $segments
     */
    public function setSegments(array $segments);
}
