<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Pipes;

interface PipeInterface
{
    /**
     * Writes data to the following filter
     *
     * @param $data
     *
     * @return mixed
     */
    public function write($data);

    /**
     * Tells the next filter that parent has finished
     */
    public function finished();
}
