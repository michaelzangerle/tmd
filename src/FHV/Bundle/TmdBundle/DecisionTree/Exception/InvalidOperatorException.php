<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Exception;

/**
 * Class InvalidOperatorException
 * @package FHV\Bundle\TmdBundle\DecisionTree\Exception
 */
class InvalidOperatorException extends DecisionTreeException
{
    function __construct($operator)
    {
        parent::__construct('An operator with the name "' . $operator . ' does not exists!');
    }
}
