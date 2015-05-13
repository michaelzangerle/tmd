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
     * @param integer $analizationType
     *
     * @return Result
     */
    public function setAnalizationType($analizationType);

    /**
     * Get analizationType
     *
     * @return integer
     */
    public function getAnalizationType();
}

