<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Track
 */
class Track
{
    const TYPE_UNKNOW = 0;
    const TYPE_BASIC = 1;
    const TYPE_GIS = 2;

    /**
     * @var integer
     */
    private $analyzationType;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $segments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->segments = new ArrayCollection();
    }

    /**
     * Set analyzationType
     *
     * @param integer $analyzationType
     *
     * @return Track
     */
    public function setAnalyzationType($analyzationType)
    {
        $this->analyzationType = $analyzationType;

        return $this;
    }

    /**
     * Get analyzationType
     *
     * @return integer
     */
    public function getAnalyzationType()
    {
        return $this->analyzationType;
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
     * Add segments
     *
     * @param Tracksegment $segments
     *
     * @return Track
     */
    public function addSegment(Tracksegment $segments)
    {
        $this->segments[] = $segments;

        return $this;
    }

    /**
     * Remove segments
     *
     * @param Tracksegment $segments
     */
    public function removeSegment(Tracksegment $segments)
    {
        $this->segments->removeElement($segments);
    }

    /**
     * Get segments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * Sets segments
     *
     * @param Tracksegment[] $segments
     */
    public function setSegments($segments)
    {
        $this->segments = $segments;
    }
}
