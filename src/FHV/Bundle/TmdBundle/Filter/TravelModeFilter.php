<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\DecisionTree\DecisionTreeInterface;
use FHV\Bundle\TmdBundle\DecisionTree\Manager\DecisionTreeManagerInterface;
use FHV\Bundle\TmdBundle\DecisionTree\Model\Result as TreeResult;
use FHV\Bundle\TmdBundle\Model\Track;

/**
 * Applies a decision tree to each given segment and sets given result for each segment
 * Class TravelModeFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class TravelModeFilter extends AbstractComponent
{
    /**
     * @var DecisionTreeInterface
     */
    protected $tree;

    /**
     * @var DecisionTreeManagerInterface
     */
    protected $dtManager;

    function __construct(DecisionTreeManagerInterface $dtManager)
    {
        parent::__construct();
        $this->dtManager = $dtManager;
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
            $this->tree = $this->dtManager->getTree($data->getAnalysisType());
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
     *
     * @param Track $track
     */
    protected function process(Track $track)
    {
        foreach ($track->getSegments() as $segment) {
            /** @var TreeResult $treeResult */
            $treeResult = $this->tree->process($segment->getFeatures());
            $result = $segment->getResult();

            // if the segment has already a type (because it was in the gpx data)
            // assume that the given type was the correct one
            // therefore if the new type is different than the given one set the given
            // one in the corrected result
            if($segment->getType() && $segment->getType() !== $treeResult->getMaxName()) {
                $result->setCorrectTransportType($segment->getType());
            }

            $result->setProbability(round($treeResult->getMaxValue() / $treeResult->getTotal(), 2));
            $result->setTransportType($treeResult->getMaxName());
            $segment->setType($treeResult->getMaxName());
        }
    }
}
