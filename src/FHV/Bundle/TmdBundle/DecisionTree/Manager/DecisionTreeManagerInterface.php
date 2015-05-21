<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Manager;

use FHV\Bundle\TmdBundle\DecisionTree\Model\Tree;

/**
 * Interface DecisionTreeManagerInterface
 * @package FHV\Bundle\TmdBundle\DecisionTree\Manager
 */
interface DecisionTreeManagerInterface
{
    /**
     * Returns a tree and writes cache file if it does not exist
     * @param $name
     * @return Tree
     */
    public function getTree($name);
}

