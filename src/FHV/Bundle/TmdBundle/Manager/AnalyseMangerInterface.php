<?php

namespace FHV\Bundle\TmdBundle\Manager;

/**
 * Interface for analyse manager which manages all analyse operations
 * Interface AnalyseMangerInterface
 * @package FHV\Bundle\TmdBundle\Manager
 */
interface AnalyseMangerInterface
{
    /**
     * Returns an overview of the analysed results per analyse mode
     *
     *
     * @return array
     */
    public function getOverview();

    /**
     * Returns a detailed analyse of a mode for each analyse type or for all modes if $mode is null
     *
     * @param string $mode
     *
     * @return array
     */
    public function getDetail($mode);
}
