<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Class TracksegmentType
 * @package FHV\Bundle\TmdBundle\Model
 */
final class TracksegmentType
{
    const UNDEFINIED = 'unknown';
    const DRIVE = 'drive';
    const BUS = 'bus';
    const TRAIN = 'train';
    const WALK = 'walk';
    const BIKE = 'bike';

    private function __construct()
    {
    }
}
