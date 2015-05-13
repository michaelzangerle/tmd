<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Class TracksegmentType
 * @package FHV\Bundle\TmdBundle\Model
 */
final class TracksegmentType
{
    const UNDEFINIED = 0;
    const DRIVE = 1;
    const BUS = 2;
    const TRAIN = 3;
    const WALK = 4;
    const BIKE = 5;

    private function __construct()
    {
    }
}
