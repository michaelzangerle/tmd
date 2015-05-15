<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Result
 *
 */
class Result implements ResultInterface
{
    /**
     * @var string
     */
    private $transportType = TracksegmentType::UNDEFINIED;

    /**
     * @var integer
     */
    private $analizationType;

    /**
     * @var float
     */
    private $probability;

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
     * @return float
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * @param float $probability
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;
    }
}
