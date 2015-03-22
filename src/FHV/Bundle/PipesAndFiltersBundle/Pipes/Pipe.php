<?php

namespace FHV\Bundle\PipesAndFiltersBundle\Pipes;

use FHV\Bundle\PipesAndFiltersBundle\Filter\FilterInterface;

/**
 * Connects to filters and writes data from one to another
 * Class Pipe
 * @package FHV\Bundle\PipesAndFiltersBundle\Pipes
 */
class Pipe implements PipeInterface
{
    /**
     * @var FilterInterface
     */
    private $targetFilter;

    function __construct(FilterInterface $inputFilter, FilterInterface $targetFilter)
    {
        $this->targetFilter = $targetFilter;
        $inputFilter->register($this);

    }

    /**
     * @inheritdoc
     */
    public function write($data, $log = null)
    {
        if ($this->targetFilter !== null) {
            $this->targetFilter->run($data, $log);
        }
    }
}
