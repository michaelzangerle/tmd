<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Feature
 * @ExclusionPolicy("all")
 */
class Feature
{
    /**
     * @Expose
     * @var float
     */
    private $key;

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
     * @param float $key
     *
     * @return Feature
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return float
     */
    public function getKey()
    {
        return $this->key;
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
