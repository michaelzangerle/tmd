<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Exception;

/**
 * Exception will be thrown when an invalid operator is given
 * Class InvalidOperatorException
 * @package FHV\Bundle\TmdBundle\DecisionTree\Exception
 */
class InvalidComparatorException extends DecisionTreeException
{
    function __construct($operator)
    {
        parent::__construct('The comparator "' . $operator . '" is not supported!');
    }
}
