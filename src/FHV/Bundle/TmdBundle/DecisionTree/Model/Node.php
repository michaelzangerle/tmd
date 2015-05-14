<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Model;

use FHV\Bundle\TmdBundle\DecisionTree\Exception\InvalidNodeException;

/**
 * Class Node
 * @package FHV\Bundle\TmdBundle\DecisionTree\Model
 */
class Node
{
    /**
     * @var Node
     */
    protected $parent;

    /**
     * @var Node
     */
    protected $left;

    /**
     * @var Node
     */
    protected $right;

    /**
     * @var Decision
     */
    protected $decision;

    /**
     * @var Result
     */
    protected $result;

    function __construct(
        Decision $decision = null,
        Node $left = null,
        Node $right = null,
        Node $parent = null,
        Result $result = null
    ) {
        $this->parent = $parent;
        $this->left = $left;
        $this->right = $right;
        $this->decision = $decision;
        $this->result = $result;
    }

    /**
     * @return Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Node
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return Node
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param Node $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param Node $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * @param Node $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return Decision
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * @param Decision $decision
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets the single values to the array
     *
     * @param Result $result
     *
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Processes the node which means apply a decision and return a child or return the result if no children exist
     *
     * @param array $values
     *
     * @return array|Node
     * @throws InvalidNodeException
     * @throws \FHV\Bundle\TmdBundle\DecisionTree\Exception\InvalidFieldException
     */
    public function process(array $values)
    {
        if ($this->decision && $this->hasChildren()) {
            if ($this->decision->applyDecision($values)) {
                return $this->left;
            } else {
                return $this->right;
            }
        } elseif ($this->result) {
            return $this->result;
        } else {
            throw new InvalidNodeException();
        }
    }

    /**
     * Checks if the node has both children
     * @return bool
     */
    protected function hasChildren()
    {
        return $this->left && $this->right;
    }
}
