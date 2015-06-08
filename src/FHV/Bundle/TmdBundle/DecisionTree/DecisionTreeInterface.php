<?php

namespace FHV\Bundle\TmdBundle\DecisionTree;

/**
 * Interface for decision trees
 * Interface DecisionTreeInterface
 * @package FHV\Bundle\TmdBundle\DecisionTree
 */
interface DecisionTreeInterface
{
    /**
     * Traverses the tree according the given values and the decisions until
     * it reaches a result which will then be returned
     *
     * @param array $values
     *
     * @return array
     */
    public function process(array $values);
}
