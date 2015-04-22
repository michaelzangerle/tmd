<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Trackpoint
 * @ExclusionPolicy("all")
 */
class Trackpoint
{
    /**
     * @Expose
     * @var float
     */
    private $lat;

    /**
     * @Expose
     * @var float
     */
    private $long;

    /**
     * @Expose
     * @var \DateTime
     */
    private $time;

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
     * Set lat
     *
     * @param float $lat
     * @return Trackpoint
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set long
     *
     * @param float $long
     * @return Trackpoint
     */
    public function setLong($long)
    {
        $this->long = $long;

        return $this;
    }

    /**
     * Get long
     *
     * @return float 
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Trackpoint
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
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
     * @return Trackpoint
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
