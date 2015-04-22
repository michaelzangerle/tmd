<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Track
 */
class Track
{
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
        $this->segments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param \FHV\Bundle\TmdBundle\Entity\Tracksegment $segments
     *
     * @return Track
     */
    public function addSegment(\FHV\Bundle\TmdBundle\Entity\Tracksegment $segments)
    {
        $this->segments[] = $segments;

        return $this;
    }

    /**
     * Remove segments
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Tracksegment $segments
     */
    public function removeSegment(\FHV\Bundle\TmdBundle\Entity\Tracksegment $segments)
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
