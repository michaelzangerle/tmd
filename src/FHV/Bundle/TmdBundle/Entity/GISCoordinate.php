<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a coordinate which is needed for the analyse method with gis data
 * GISCoordinate
 */
class GISCoordinate
{
    const HIGHWAY_TYPE = 'highway';
    const RAILWAY_TYPE = 'railway';
    const BUSSTOP_TYPE = 'busstop';

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $id;

    /**
     * GISCoordinate constructor.
     *
     * @param float $latitude
     * @param float $longitude
     * @param string $type
     */
    public function __construct($latitude, $longitude, $type)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->type = $type;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return GISCoordinate
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
     * @return GISCoordinate
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

    /**
     * Set type
     *
     * @param string $type
     *
     * @return GISCoordinate
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
}
