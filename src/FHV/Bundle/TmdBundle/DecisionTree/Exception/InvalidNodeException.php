<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Exception;

/**
 * Exception will be thrown when a tree node has an invalid state (no children and/or decision and no result)
 * Class InvalidNodeException
 * @package FHV\Bundle\TmdBundle\DecisionTree\Exception
 */
class InvalidNodeException extends DecisionTreeException
{
    function __construct()
    {
        parent::__construct(
            'The node seems to have an invalid status. A node has to have children and a decision or a result!'
        );
    }
}
