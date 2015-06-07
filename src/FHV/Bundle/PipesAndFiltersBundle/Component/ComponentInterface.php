<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Component;

use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;
use FHV\Bundle\PipesAndFiltersBundle\Pipes\PipeInterface;

/**
 * Interface for pipes and filter component (filter, sink)
 * Interface ComponentInterface
 * @package FHV\Bundle\PipesAndFiltersBundle\Component
 */
interface ComponentInterface
{
    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws ComponentException
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
