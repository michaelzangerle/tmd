<?php

namespace FHV\Bundle\TmdBundle\Exception;

/**
 * Class ResultNotFoundException
 * @package FHV\Bundle\TmdBundle\Exception
 */
class ResultNotFoundException extends ResultException
{
    function __construct($id)
    {
        parent::__construct('Result entity with id ' . $id . ' not found!');
    }
}
