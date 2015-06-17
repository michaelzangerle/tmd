<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Interface ResultInterface
 * @package FHV\Bundle\TmdBundle\Model
 */
interface ResultInterface
{
    /**
     * Set transportType
     *
     * @param integer $transportType
     *
     * @return Result
     */
    public function setTransportType($transportType);

    /**
     * Get transportType
     *
     * @return integer
     */
    public function getTransportType();

    /**
     * Set analizationType
     *
     * @param integer $analisationType
     *
     * @return Result
     */
    public function setAnalisationType($analisationType);

    /**
     * Get analizationType
     *
     * @return integer
     */
    public function getAnalisationType();

    /**
     * @return int
     */
    public function getCorrectTransportType();

    /**
     * @param int $correctAnalysationType
     */
    public function setCorrectTransportType($correctAnalysationType);
}

