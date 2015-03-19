<?php

/**
 * Filters and removes single trackpoints from an array of trackpoints
 * Class TrackPointFilter
 */
class TrackPointFilter
{


    public function filter(array $trackPoints)
    {
        if ($trackPoints !== null and count($trackPoints) > 1) {
            $result = [];
            $length = count($trackPoints) - 1;
            $i = 0;

            $result[] = $trackPoints[$i];
            while ($i < $length) {

            }

            return $result;
        }

        return null;
    }
}
