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
    private $latitude;

    /**
     * @Expose
     * @var float
     */
    private $longitude;

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
     * @var Tracksegment
     */
    private $segment;

    /**
     * Set time
     *
     * @param \DateTime $time
     *
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
     * @param Tracksegment $segment
     *
     * @return Trackpoint
     */
    public function setSegment(Tracksegment $segment)
    {
        $this->segment = $segment;

        return $this;
    }

    /**
     * Get segment
     *
     * @return Tracksegment
     */
    public function getSegment()
    {
        return $this->segment;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Trackpoint
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Trackpoint
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
