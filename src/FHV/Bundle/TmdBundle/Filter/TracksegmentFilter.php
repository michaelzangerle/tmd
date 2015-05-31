<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\AbstractFilter;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Model\Tracksegment;
use FHV\Bundle\TmdBundle\Model\TracksegmentInterface;
use FHV\Bundle\TmdBundle\Util\TrackpointUtil;
use FHV\Bundle\TmdBundle\Model\Feature;

/**
 * Creates a segment from trackpoints
 * Class SegmentFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class TracksegmentFilter extends AbstractFilter
{
    /**
     * @var int
     */
    protected $minTrackPointsPerSegment;

    /**
     * Util obj for calculations
     * @var TrackpointUtil
     */
    protected $util;

    /**
     * @var float
     */
    protected $maxVelocityForNearlyStopPoints;

    /**
     * @var int
     */
    protected $maxTimeWithoutMovement;

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws FilterException
     */
    public function run($data)
    {
        if ($data !== null &&
            array_key_exists('track', $data) && $data['track'] !== null &&
            array_key_exists('analyseType', $data) && $data['analyseType'] !== null
        ) {
            /** @var TracksegmentInterface $segment */
            foreach ($data['track']->getSegments() as $segment) {
                $features = $this->getSegmentFeatures($segment->getTrackPoints(), $segment->getType());
                $segment->setFeatures($features);
            }
            $this->write($data['track']);
        } else {
            if (array_key_exists('trackPoints', $data) && array_key_exists('analyseType', $data)) {
                $features = $this->getSegmentFeatures($data['trackPoints'], $data['type']);
                $segment = $this->createSegment($features);
                $this->write(
                    [
                        'segment' => $segment,
                        'analyseType' => $data['analyseType']
                    ]
                );
            } else {
                throw new InvalidArgumentException('SegmentFilter: Data param should contain trackpoints or a track with the analyse type!');
            }
        }
    }

    function __construct(
        TrackpointUtil $util,
        $minTrackPointsPerSegment,
        $maxVelocityForNearlyStopPoints,
        $maxTimeWithoutMovement
    ) {
        parent::__construct();
        $this->util = $util;
        $this->minTrackPointsPerSegment = $minTrackPointsPerSegment;
        $this->maxVelocityForNearlyStopPoints = $maxVelocityForNearlyStopPoints;
        $this->maxTimeWithoutMovement = $maxTimeWithoutMovement;
    }

    /**
     * Returns a segment for the given trackpoints
     *
     * @param TrackpointInterface[] $trackPoints
     * @param string|null           $type
     *
     * @return array with calculated features
     * @throws InvalidArgumentException
     */
    public function getSegmentFeatures(array $trackPoints, $type = null)
    {
        $totalAcceleration = 0;
        $amountOfTrackPoints = count($trackPoints) - 1;
        $totalVelocity = 0;
        $maxAcceleration = 0;
        $maxVelocity = 0;
        $distance = 0;
        $time = 0;
        $prevVelocity = 0;
        $accTrackPoints = 0;
        $gpsTrackPoints[] = $trackPoints[0];
        $lowSpeedTimeCounter = 0;
        $stopCounter = 0;

        if ($amountOfTrackPoints + 1 >= $this->minTrackPointsPerSegment) {
            for ($i = 0; $i < $amountOfTrackPoints; $i++) {
                $tp1 = $trackPoints[$i];
                $tp2 = $trackPoints[$i + 1];

                $gpsTrackPoints[] = $tp2;

                $currentDistance = $this->util->calcDistance($tp1, $tp2);
                $currentTime = $this->util->calcTime($tp2, $tp1);

                $currentVelocity = $this->util->calcVelocity($currentDistance, $currentTime);
                $currentAcceleration = $this->util->calcAcceleration($currentVelocity, $currentTime, $prevVelocity);

                $distance += $currentDistance;
                $time += $currentTime;
                $totalVelocity += $currentVelocity;

                if ($currentVelocity > $maxVelocity) {
                    $maxVelocity = $currentVelocity;
                }

                if ($currentAcceleration > $maxAcceleration) {
                    $maxAcceleration = $currentAcceleration;
                }

                if ($currentAcceleration > 0) {
                    $totalAcceleration += $currentAcceleration;
                    $accTrackPoints++;
                }

                // bilijecki counts the points with speed below a certain value
                // when a certain amount is below a the velocity threshold it
                // counts as a stop
                if ($currentVelocity < $this->maxVelocityForNearlyStopPoints) {
                    $lowSpeedTimeCounter += $currentTime;

                    if ($lowSpeedTimeCounter >= $this->maxTimeWithoutMovement) {
                        $stopCounter++;
                        $lowSpeedTimeCounter = 0;
                    }
                } else {
                    $lowSpeedTimeCounter = 0;
                }

                $prevVelocity = $currentAcceleration;
            }

            return [
                Feature::MEAN_ACCELERATION => $totalAcceleration / $accTrackPoints,
                Feature::MEAN_VELOCITY => $totalVelocity / $amountOfTrackPoints,
                Feature::MAX_ACCELERATION => $maxAcceleration,
                Feature::MAX_VELOCITY => $maxVelocity,
                Feature::STOP_RATE => $stopCounter / $distance,
                'time' => $time,
                'distance' => $distance,
                'startPoint' => $gpsTrackPoints[0],
                'endPoint' => $gpsTrackPoints[$amountOfTrackPoints],
                'trackPoints' => $gpsTrackPoints,
                'type' => $type
            ];
        }

        throw new InvalidArgumentException(
            'SegmentFilter: There should at least be ' . $this->minTrackPointsPerSegment . ' trackpoints present!'
        );
    }

    /**
     * Creates and sets the features for a segment
     *
     * @param $features
     *
     * @return TracksegmentInterface
     */
    protected function createSegment($features)
    {
        $seg = new Tracksegment();
        $seg->setFeatures($features);
        return $seg;
    }
}
