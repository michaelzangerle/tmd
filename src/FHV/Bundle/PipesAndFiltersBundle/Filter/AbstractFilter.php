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
    protected $pipes;

    /**
     * Will be set if parent filter is finished
     * @var Boolean
     */
    protected $parentHasFinished;

    function __construct()
    {
        $this->pipes = [];
        $this->parentHasFinished = false;
    }

    /**
     * @inheritdoc
     */
    public function getParentHasFinished()
    {
        return $this->parentHasFinished;
    }

    /**
     * @inheritdoc
     */
    public function parentHasFinished()
    {
        $this->parentHasFinished = true;
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

    /**
     * @inheritdoc
     */
    public function finished()
    {
        foreach ($this->pipes as $pipe) {
            $pipe->finished();
        }
    }
}
