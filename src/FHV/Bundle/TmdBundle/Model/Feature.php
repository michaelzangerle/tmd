<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Class Feature
 * @package FHV\Bundle\TmdBundle\Model
 */
final class Feature
{
    const STOP_RATE = 'stopRate';
    const MEAN_VELOCITY = 'meanVelocity';
    const MEAN_ACCELERATION = 'meanAcceleration';
    const MAX_VELOCITY = 'maxVelocity';
    const MAX_ACCELERATION = 'maxAcceleration';

    private function __construct()
    {
    }
}
