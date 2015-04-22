<?php

namespace FHV\Bundle\TmdBundle\Entity;

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
     * @var \FHV\Bundle\TmdBundle\Entity\Trackpoint
     */
    private $start;

    /**
     * @Expose
     * @var \FHV\Bundle\TmdBundle\Entity\Trackpoint
     */
    private $end;

    /**
     * @Expose
     * @var \FHV\Bundle\TmdBundle\Entity\Result
     */
    private $result;

    /**
     * @Expose
     * @var \Doctrine\Common\Collections\Collection
     */
    private $trackpoints;

    /**
     * @Expose
     * @var \Doctrine\Common\Collections\Collection
     */
    private $features;

    /**
     * @var \FHV\Bundle\TmdBundle\Entity\Track
     */
    private $track;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trackpoints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->features = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \FHV\Bundle\TmdBundle\Entity\Trackpoint $start
     *
     * @return Tracksegment
     */
    public function setStart(\FHV\Bundle\TmdBundle\Entity\Trackpoint $start = null)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \FHV\Bundle\TmdBundle\Entity\Trackpoint
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Trackpoint $end
     *
     * @return Tracksegment
     */
    public function setEnd(\FHV\Bundle\TmdBundle\Entity\Trackpoint $end = null)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \FHV\Bundle\TmdBundle\Entity\Trackpoint
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set result
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Result $result
     *
     * @return Tracksegment
     */
    public function setResult(\FHV\Bundle\TmdBundle\Entity\Result $result = null)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return \FHV\Bundle\TmdBundle\Entity\Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Add trackpoints
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Trackpoint $trackpoints
     *
     * @return Tracksegment
     */
    public function addTrackpoint(\FHV\Bundle\TmdBundle\Entity\Trackpoint $trackpoints)
    {
        $this->trackpoints[] = $trackpoints;

        return $this;
    }

    /**
     * Remove trackpoints
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Trackpoint $trackpoints
     */
    public function removeTrackpoint(\FHV\Bundle\TmdBundle\Entity\Trackpoint $trackpoints)
    {
        $this->trackpoints->removeElement($trackpoints);
    }

    /**
     * Get trackpoints
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrackpoints()
    {
        return $this->trackpoints;
    }

    /**
     * Add features
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Feature $features
     *
     * @return Tracksegment
     */
    public function addFeature(\FHV\Bundle\TmdBundle\Entity\Feature $features)
    {
        $this->features[] = $features;

        return $this;
    }

    /**
     * Remove features
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Feature $features
     */
    public function removeFeature(\FHV\Bundle\TmdBundle\Entity\Feature $features)
    {
        $this->features->removeElement($features);
    }

    /**
     * Get features
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set track
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Track $track
     *
     * @return Tracksegment
     */
    public function setTrack(\FHV\Bundle\TmdBundle\Entity\Track $track)
    {
        $this->track = $track;

        return $this;
    }

    /**
     * Get track
     *
     * @return \FHV\Bundle\TmdBundle\Entity\Track
     */
    public function getTrack()
    {
        return $this->track;
    }
}
