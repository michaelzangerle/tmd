<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Pipes;

/**
 * Interface for pipes which connects pipes and filter components
 * Interface PipeInterface
 * @package FHV\Bundle\PipesAndFiltersBundle\Pipes
 */
interface PipeInterface
{
    /**
     * Writes data to the connected filter
     *
     * @param $data
     *
     * @return mixed
     */
    public function write($data);

    /**
     * Tells the next filter that the parent filter has finished work
     */
    public function finished();
}
