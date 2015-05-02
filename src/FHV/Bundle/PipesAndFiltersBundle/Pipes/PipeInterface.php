<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Pipes;

/**
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
