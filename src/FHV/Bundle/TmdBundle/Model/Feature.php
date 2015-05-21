<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Class Feature
 * @package FHV\Bundle\TmdBundle\Model
 */
final class Feature
{
    const STOP_RATE = 'stoprate';
    const MEAN_VELOCITY = 'meanvelocity';
    const MEAN_ACCELERATION = 'meanacceleration';
    const MAX_VELOCITY = 'maxvelocity';
    const MAX_ACCELERATION = 'maxacceleration';

    private function __construct()
    {
    }
}
