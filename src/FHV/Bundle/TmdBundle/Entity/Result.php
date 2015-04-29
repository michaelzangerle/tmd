<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FHV\Bundle\TmdBundle\Model\TracksegmentType;

/**
 * Result
 */
class Result
{
    /**
     * @var integer
     */
    private $transportType = TracksegmentType::UNDEFINIED;

    /**
     * @var boolean
     */
    private $correctedByUser = false;

    /**
     * @var integer
     */
    private $analizationType;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $calcPrecision;

    /**
     * Set transportType
     *
     * @param integer $transportType
     *
     * @return Result
     */
    public function setTransportType($transportType)
    {
        $this->transportType = $transportType;

        return $this;
    }

    /**
     * Get transportType
     *
     * @return integer
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    /**
     * Set correctedByUser
     *
     * @param boolean $correctedByUser
     *
     * @return Result
     */
    public function setCorrectedByUser($correctedByUser)
    {
        $this->correctedByUser = $correctedByUser;

        return $this;
    }

    /**
     * Get correctedByUser
     *
     * @return boolean
     */
    public function getCorrectedByUser()
    {
        return $this->correctedByUser;
    }

    /**
     * Set analizationType
     *
     * @param integer $analizationType
     *
     * @return Result
     */
    public function setAnalizationType($analizationType)
    {
        $this->analizationType = $analizationType;

        return $this;
    }

    /**
     * Get analizationType
     *
     * @return integer
     */
    public function getAnalizationType()
    {
        return $this->analizationType;
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
     * Set calcPrecision
     *
     * @param float $calcPrecision
     *
     * @return Result
     */
    public function setCalcPrecision($calcPrecision)
    {
        $this->calcPrecision = $calcPrecision;

        return $this;
    }

    /**
     * Get calcPrecision
     *
     * @return float
     */
    public function getCalcPrecision()
    {
        return $this->calcPrecision;
    }
}
