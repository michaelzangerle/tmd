<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Result
 * @package FHV\Bundle\TmdBundle\Model
 */
class Result implements ResultInterface
{
    /**
     * @var string
     */
    private $transportType = TracksegmentType::UNDEFINED;

    /**
     * @var string
     */
    private $analisationType;

    /**
     * @var float
     */
    private $probability;

    /**
     * @var string
     */
    private $correctTransportType;

    /**
     * Set transportType
     *
     * @param string $transportType
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
     * @return string
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

    /**
     * Set analizationType
     *
     * @param string $analisationType
     *
     * @return Result
     */
    public function setAnalisationType($analisationType)
    {
        $this->analisationType = $analisationType;

        return $this;
    }

    /**
     * Get analizationType
     *
     * @return string
     */
    public function getAnalisationType()
    {
        return $this->analisationType;
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

    /**
     * @return string
     */
    public function getCorrectTransportType()
    {
        return $this->correctTransportType;
    }

    /**
     * @param string $correctTransportType
     */
    public function setCorrectTransportType($correctTransportType)
    {
        $this->correctTransportType = $correctTransportType;
    }
}
