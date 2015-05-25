<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Exception;

/**
 * Exception will be thrown when a field would be accessed which does not exist in the given data
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
