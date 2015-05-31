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
    const PUBLIC_TRANSPORT_STATION_CLOSENESS = 'ptscloseness';
    const RAIL_CLOSENESS = 'railcloseness';
    const HIGHWAY_CLOSENESS = 'highwaycloseness';

    private function __construct()
    {
    }
}
