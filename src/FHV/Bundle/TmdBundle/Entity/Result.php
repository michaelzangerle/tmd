<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FHV\Bundle\TmdBundle\Model\TracksegmentType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Result
 * @ExclusionPolicy("all")
 */
class Result
{
    /**
     * @var \FHV\Bundle\TmdBundle\Entity\Tracksegment
     */
    private $segment;

    /**
     * @Expose
     * @var integer
     */
    private $transportType = TracksegmentType::UNDEFINIED;

    /**
     * @Expose
     * @var boolean
     */
    private $correctedByUser = false;

    /**
     * @Expose
     * @var string
     */
    private $analyseType;

    /**
     * @Expose
     * @var integer
     */
    private $id;

    /**
     * @Expose
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
     * @param string $analyseType
     *
     * @return Result
     */
    public function setAnalyseType($analyseType)
    {
        $this->analyseType = $analyseType;

        return $this;
    }

    /**
     * Get analizationType
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

    /**
     * Set segment
     *
     * @param \FHV\Bundle\TmdBundle\Entity\Tracksegment $segment
     *
     * @return Result
     */
    public function setSegment(\FHV\Bundle\TmdBundle\Entity\Tracksegment $segment = null)
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
