<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Class BoundingBox
 * @package FHV\Bundle\TmdBundle\Model
 */
class BoundingBox
{
    /**
     * @var float
     */
    private $top;

    /**
     * @var float
     */
    private $bottom;

    /**
     * @var float
     */
    private $left;

    /**
     * @var float
     */
    private $right;

    /**
     * BoundingBox constructor.
     *
     * @param float $top
     * @param float $bottom
     * @param float $left
     * @param float $right
     */
    public function __construct($top, $bottom, $left, $right)
    {
        $this->top = $top;
        $this->bottom = $bottom;
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return float
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     * @return float
     */
    public function getBottom()
    {
        return $this->bottom;
    }

    /**
     * @return float
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return float
     */
    public function getRight()
    {
        return $this->right;
    }
}
