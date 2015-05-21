<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Manager;

/**
 * Class NodeDummy
 * @package FHV\Bundle\TmdBundle\DecisionTree\Manager
 */
class NodeDummy
{
    /**
     * @var string
     */
    protected $left;

    /**
     * @var string
     */
    protected $right;

    /**
     * @return string
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param string $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * @return string
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param string $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @var string
     */
    protected $parent;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $feature;

    /**
     * @var string
     */
    protected $comparator;

    /**
     * @var float
     */
    protected $value;

    /**
     * @var array
     */
    protected $result;

    /**
     * NodeDummy constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * @param string $feature
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    /**
     * @return string
     */
    public function getComparator()
    {
        return $this->comparator;
    }

    /**
     * @param string $comparator
     */
    public function setComparator($comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns results concatenated as string
     * @return string
     */
    public function getResult()
    {
        if ($this->result) {
            return implode(',', $this->result);
        }
        return null;
    }

    /**
     * @param array $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
