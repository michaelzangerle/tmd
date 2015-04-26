<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\PipeInterface;

interface FilterInterface
{
    /**
     * Starts a filter and processes the given data
     * @param $data
     * @throws FilterException
     */
    public function run($data);

    /**
     * Registers a pipe
     * @param PipeInterface $pipe
     * @return boolean
     */
    public function register(PipeInterface $pipe);

    /**
     * Writes data to all connected pipes
     * @param $data
     */
    public function write($data);

    /**
     * Will be called when the parent filter has finished
     */
    public function parentHasFinished();

    /**
     * Returns true if parent filter has finished
     */
    public function getParentHasFinished();

    /**
     * Tells all pipes that this filter has finished
     */
    public function finished();
}
