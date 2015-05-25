<?php

namespace FHV\Bundle\TmdBundle\Exception;

use Exception;

/**
 * General exception for rest apis
 * Class RestException
 * @package FHV\Bundle\TmdBundle\Exception
 */
class RestException extends Exception
{
    public function toArray()
    {
        return array(
            'code' => $this->code,
            'message' => $this->message
        );
    }
}
