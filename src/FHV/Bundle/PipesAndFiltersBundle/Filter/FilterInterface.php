<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Pipes\PipeInterface;
use FHV\Bundle\TmdBundle\Filter\Exception\FilterException;

interface FilterInterface
{
    /**
     * Starts a filter and processes the given data
     * @param $data
     * @param $log
     * @throws FilterException
     */
    public function run($data, $log);

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
}
