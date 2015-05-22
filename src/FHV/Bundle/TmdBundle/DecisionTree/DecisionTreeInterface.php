<?php

namespace FHV\Bundle\TmdBundle\DecisionTree;

/**
 * Interface DecisionTreeInterface
 * @package FHV\Bundle\TmdBundle\DecisionTree
 */
interface DecisionTreeInterface {

    /**
     * Process values by tree
     *
     * @param array $values
     *
     * @return array
     */
    public function process(array $values);
}