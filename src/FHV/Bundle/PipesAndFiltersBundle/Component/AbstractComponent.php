<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Component;

use FHV\Bundle\PipesAndFiltersBundle\Pipes\PipeInterface;

/**
 * Abstract implementation of a pipes and filter component (filter, sink)
 * Class AbstractComponent
 * @package FHV\Bundle\PipesAndFiltersBundle\Component
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * Pipes to write the output to
     * @var PipeInterface[]
     */
    protected $pipes;

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

    /**
     * @inheritdoc
     */
    public function finished()
    {
        foreach ($this->pipes as $pipe) {
            $pipe->finished();
        }
    }

    /**
     * @inheritdoc
     */
    public function parentHasFinished()
    {
        $this->finished();
    }
}
