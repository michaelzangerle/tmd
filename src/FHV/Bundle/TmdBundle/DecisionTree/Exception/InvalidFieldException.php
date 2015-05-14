<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Exception;

/**
 * Class InvalidFiedlException
 * @package FHV\Bundle\TmdBundle\DecisionTree\Exception
 */
class InvalidFieldException extends DecisionTreeException
{

    function __construct($field)
    {
        parent::__construct('A field with the name "' . $field . ' does not exists!');
    }
}
