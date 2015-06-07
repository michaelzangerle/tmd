<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Pipes;

use FHV\Bundle\PipesAndFiltersBundle\Component\ComponentInterface;

/**
 * Connects two pipes and filter components and writes data from one to another
 * Class Pipe
 * @package FHV\Bundle\PipesAndFiltersBundle\Pipes
 */
class Pipe implements PipeInterface
{
    /**
     * @var ComponentInterface
     */
    private $targetFilter;

    function __construct(ComponentInterface $inputFilter, ComponentInterface $targetFilter)
    {
        $this->targetFilter = $targetFilter;
        $inputFilter->register($this);
    }

    /**
     * @inheritdoc
     */
    public function finished()
    {
        $this->targetFilter->parentHasFinished();
    }

    /**
     * @inheritdoc
     */
    public function write($data)
    {
        if ($this->targetFilter !== null) {
            $this->targetFilter->run($data);
        }
    }
}
