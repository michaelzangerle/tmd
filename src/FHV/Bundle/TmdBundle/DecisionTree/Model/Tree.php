<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Model;

/**
 * Represents a tree and hold a reference to the root node
 * Class Tree
 * @package FHV\Bundle\TmdBundle\DecisionTree\Model
 */
class Tree {

    /**
     * @var Node
     */
    protected $root;

    function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * @return Node
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param Node $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * Processes a array of values and returns a result
     *
     * @param array $values
     *
     * @return array
     */
    public function process(array $values) {
        $node = $this->root->process($values);
        while($node !== null && $node instanceof Node) {
            $node = $node->process($values);
        }
        return $node;
    }
}
