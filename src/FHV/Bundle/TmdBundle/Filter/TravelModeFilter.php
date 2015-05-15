<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\DecisionTree\BasicDecissionTree;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Result as TreeResult;
use FHV\Bundle\TmdBundle\Model\Track;

/**
 * Class TravelModeFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class TravelModeFilter extends AbstractFilter
{
    /**
     * @var BasicDecissionTree
     */
    protected $tree;

    function __construct()
    {
        parent::__construct();
        $this->tree = new BasicDecissionTree();
    }

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws InvalidArgumentException
     */
    public function run($data)
    {
        if ($data !== null && $data instanceof Track) {
            $this->process($data);
            $this->write($data);
        } else {
            throw new InvalidArgumentException(
                'TravelModeFilter: Data param should be instance of track!'
            );
        }
    }

    /**
     * Processes all segments through the decision tree and sets the result
     * @param Track $track
     */
    protected function process(Track $track)
    {
        foreach($track->getSegments() as $segment){
            /** @var TreeResult $treeResult */
            $treeResult = $this->tree->process($segment->getFeatures());

            $result = $segment->getResult();
            $result->setProbability(round($treeResult->getMaxValue()/$treeResult->getTotal(),2));
            $result->setTransportType($treeResult->getMaxName());
            $segment->setType($treeResult->getMaxName());
        }
    }
}
