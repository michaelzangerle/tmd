<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a track with its segments and the type of analyse
 * Track
 */
class Track
{
    /**
     * @var string
     */
    private $analyseType;

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
     * Set analyseType
     *
     * @param string $analyseType
     *
     * @return Track
     */
    public function setAnalyseType($analyseType)
    {
        $this->analyseType = $analyseType;

        return $this;
    }

    /**
     * Get analyseType
     *
     * @return string
     */
    public function getAnalyseType()
    {
        return $this->analyseType;
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
}
