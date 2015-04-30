<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
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
     * @var Trackpoint
     */
    private $start;

    /**
     * @Expose
     * @var Trackpoint
     */
    private $end;

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
     * @var integer
     */
    private $seconds;

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
     * Set start
     *
     * @param Trackpoint $start
     *
     * @return Tracksegment
     */
    public function setStart(Trackpoint $start = null)
    {
        $this->start = $start;

        return $this;
    }
    
    /**
     * Get start
     *
     * @return Trackpoint
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param Trackpoint $end
     *
     * @return Tracksegment
     */
    public function setEnd(Trackpoint $end = null)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return Trackpoint
     */
    public function getEnd()
    {
        return $this->end;
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
     * Set trackpoints
     *
     * @param Collection $trackpoints
     *
     * @return Tracksegment
     */
    public function setTrackpoints(Collection $trackpoints)
    {
        $this->trackpoints = $trackpoints;

        return $this;
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
     * @var float
     */
    private $distance = 0;

    /**
     * Set distance
     *
     * @param float $distance
     * @return Tracksegment
     */
    public function setDistance($distance)
    {
        $this->distance = round($distance,3);

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
     * @param integer $seconds
     * @return Tracksegment
     */
    public function setSeconds($seconds)
    {
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * Get seconds
     *
     * @return integer 
     */
    public function getSeconds()
    {
        return $this->seconds;
    }
}
