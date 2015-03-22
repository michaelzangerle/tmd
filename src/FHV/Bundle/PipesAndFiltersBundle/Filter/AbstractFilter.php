<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Pipes\PipeInterface;

/**
 * Abstract implementation of a filter
 * Class AbstractFilter
 * @package FHV\Bundle\PipesAndFiltersBundle\Filter
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * Pipes to write the output to
     * @var PipeInterface[]
     */
    private $pipes;

    function __construct()
    {
        $this->pipes = [];
    }

    /**
     * @inheritdoc
     */
    public function write($data)
    {
        foreach ($this->pipes as $pipe) {
            $pipe->write($data);
        }
    }

    /**
     * @inheritdoc
     */
    public function register(PipeInterface $pipe)
    {
        if ($pipe !== null) {
            $this->pipes[] = $pipe;

            return true;
        }

        return false;
    }

}
