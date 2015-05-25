<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\PipeInterface;

/**
 * Interface for filters
 * Interface FilterInterface
 * @package FHV\Bundle\PipesAndFiltersBundle\Filter
 */
interface FilterInterface
{
    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws FilterException
     */
    public function run($data);

    /**
     * Registers a pipe at a filter
     *
     * @param PipeInterface $pipe
     *
     * @return boolean
     */
    public function register(PipeInterface $pipe);

    /**
     * Writes data to all connected pipes
     *
     * @param $data
     */
    public function write($data);

    /**
     * Will be called when the parent filter has finished work
     */
    public function parentHasFinished();

    /**
     * Tells all pipes that this filter has finished
     */
    public function finished();
}
