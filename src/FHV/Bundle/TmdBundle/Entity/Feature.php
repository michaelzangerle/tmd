<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Represents a feature of a segment (velocity, acceleration, ...)
 * Feature
 * @ExclusionPolicy("all")
 */
class Feature
{
    /**
     * @Expose
     * @var string
     */
    private $description;

    /**
     * @Expose
     * @var float
     */
    private $value;

    /**
     * @Expose
     * @var integer
     */
    private $id;

    /**
     * @var \FHV\Bundle\TmdBundle\Entity\Tracksegment
     */
    private $segment;

    /**
     * Set key
     *
     * @param string $description
     *
     * @return Feature
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return Feature
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
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
     * Set segment
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Tracksegment $segment
     *
     * @return Feature
     */
    public function setSegment(\FHV\Bundle\TmdBundle\Entity\Tracksegment $segment)
    {
        $this->segment = $segment;

        return $this;
    }

    /**
     * Get segment
     *
     * @return \FHV\Bundle\TmdBundle\Entity\Tracksegment
     */
    public function getSegment()
    {
        return $this->segment;
    }
}
