<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Represents a segment of a track and contains features, trackpoints and a result
 * Tracksegment
 * @ExclusionPolicy("all")
 */
class Tracksegment
{
    /**
     * @Expose
     * @var integer
     */
    private $id;

    /**
     * @Expose
     * @var Result
     */
    private $result;

    /**
     * @Expose
     * @var Collection
     */
    private $trackpoints;

    /**
     * @Expose
     * @var Collection
     */
    private $features;

    /**
     * @var Track
     */
    private $track;

    /**
     * @Expose
     * @var integer
     */
    private $time = 0;

    /**
     * @Expose
     * @var float
     */
    private $distance = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trackpoints = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set result
     *
     * @param Result $result
     *
     * @return Tracksegment
     */
    public function setResult(Result $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Add trackpoints
     *
     * @param Trackpoint $trackpoints
     *
     * @return Tracksegment
     */
    public function addTrackpoint(Trackpoint $trackpoints)
    {
        $this->trackpoints[] = $trackpoints;

        return $this;
    }

    /**
     * Remove trackpoints
     *
     * @param Trackpoint $trackpoints
     */
    public function removeTrackpoint(Trackpoint $trackpoints)
    {
        $this->trackpoints->removeElement($trackpoints);
    }

    /**
     * Get trackpoints
     *
     * @return Collection
     */
    public function getTrackpoints()
    {
        return $this->trackpoints;
    }

    /**
     * Add features
     *
     * @param Feature $features
     *
     * @return Tracksegment
     */
    public function addFeature(Feature $features)
    {
        $this->features[] = $features;

        return $this;
    }

    /**
     * Remove features
     *
     * @param Feature $features
     */
    public function removeFeature(Feature $features)
    {
        $this->features->removeElement($features);
    }

    /**
     * Get features
     *
     * @return Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set track
     *
     * @param Track $track
     *
     * @return Tracksegment
     */
    public function setTrack(Track $track)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return Track
     */
    public function getTrack()
    {
        return $this->track;
    }

    /**
     * Set distance
     *
     * @param float $distance
     *
     * @return Tracksegment
     */
    public function setDistance($distance)
    {
        $this->distance = round($distance, 3);

        return $this;
    }

    /**
     * Get distance
     *
     * @return float
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * Set seconds
     *
     * @param integer $time
     *
     * @return Tracksegment
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get seconds
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }
}
