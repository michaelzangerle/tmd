<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Model\TrackInterface;
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
class TracksegmentFilter extends AbstractComponent
{
    /**
     * Variables used to determine the features of a segment
     */
    protected $currentTp;
    protected $totalAcceleration;
    protected $amountOfTrackPoints;
    protected $totalVelocity;
    protected $maxAcceleration;
    protected $maxVelocity;
    protected $distance;
    protected $time;
    protected $prevAcceleration;
    protected $accTrackPoints;
    protected $gpsTrackPoints;
    protected $lowSpeedTimeCounter;
    protected $stopCounter;

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
     * @throws ComponentException
     */
    public function run($data)
    {
        if ($data !== null &&
            array_key_exists('track', $data) && $data['track'] !== null &&
            array_key_exists('analyseType', $data) && $data['analyseType'] !== null
        ) {
            $this->handleTrackData($data);
        } else {
            $this->handleTrackpointsData($data);
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
     * Resets all the variables needed for segment features
     */
    protected function resetSegmentValues()
    {
        $this->currentTp = null;
        $this->totalAcceleration = 0;
        $this->amountOfTrackPoints = 0;
        $this->totalVelocity = 0;
        $this->maxAcceleration = 0;
        $this->maxVelocity = 0;
        $this->distance = 0;
        $this->time = 0;
        $this->prevAcceleration = 0;
        $this->accTrackPoints = 0;
        $this->gpsTrackPoints = [];
        $this->lowSpeedTimeCounter = 0;
        $this->stopCounter = 0;
    }

    /**
     * Returns a segment for the given trackpoints
     *
     * @param TrackpointInterface[] $trackPoints
     * @param string|null $type
     *
     * @return array with calculated features
     * @throws InvalidArgumentException
     */
    public function getSegmentFeatures(array $trackPoints, $type = null)
    {
        $this->resetSegmentValues();
        $this->amountOfTrackPoints = count($trackPoints) - 1;

        if ($this->amountOfTrackPoints + 1 >= $this->minTrackPointsPerSegment) {

            $this->firstTrackpointHandling($trackPoints[0]);

            for ($i = 0; $i < $this->amountOfTrackPoints; $i++) {
                $tp1 = $trackPoints[$i];
                $tp2 = $trackPoints[$i + 1];
                $this->gpsTrackPoints[] = $tp2;
                $this->currentTp = $tp2;

                $currentDistance = $this->util->calcDistance($tp1, $tp2);
                $currentTime = $this->util->calcTime($tp2, $tp1);
                $currentVelocity = $this->util->calcVelocity($currentDistance, $currentTime);
                $currentAcceleration = $this->util->calcAcceleration(
                    $currentVelocity,
                    $currentTime,
                    $this->prevAcceleration
                );

                $this->distance += $currentDistance;
                $this->time += $currentTime;
                $this->handleFeatures($currentVelocity, $currentAcceleration, $currentTime);
            }

            $this->lastTrackpointHandling($trackPoints[0]);

            return $this->getResultForCurrentSegment($type);
        }

        throw new InvalidArgumentException(
            'SegmentFilter: There should at least be '.$this->minTrackPointsPerSegment.' trackpoints present!'
        );
    }

    /**
     * Handle values for different features
     *
     * @param $currentVelocity
     * @param $currentAcceleration
     * @param $currentTime
     */
    protected function handleFeatures($currentVelocity, $currentAcceleration, $currentTime)
    {
        $this->handleVelocity($currentVelocity);
        $this->handleAcceleration($currentAcceleration);
        $this->handlePossibleStop($currentVelocity, $currentTime);
    }

    /**
     * Special handling for the first trackpoint of a segment
     *
     * @param TrackpointInterface $tp
     */
    protected function firstTrackpointHandling(TrackpointInterface $tp)
    {
        $this->addTrackpoint($tp);
    }

    /**
     * Special handling for the last trackpoint of segment
     *
     * @param TrackpointInterface $tp
     */
    protected function lastTrackpointHandling(TrackpointInterface $tp)
    {
        $this->addTrackpoint($tp);
    }

    /**
     * Adds a trackpoint to the current gps trackpoint array
     *
     * @param TrackpointInterface $tp
     */
    protected function addTrackpoint(TrackpointInterface $tp)
    {
        $this->gpsTrackPoints[] = $tp;
    }

    /**
     * Returns the result for the current segment
     *
     * @param $type
     *
     * @return array
     */
    protected function getResultForCurrentSegment($type)
    {
        return [
            Feature::MEAN_ACCELERATION => $this->totalAcceleration / $this->accTrackPoints,
            Feature::MEAN_VELOCITY => $this->totalVelocity / $this->amountOfTrackPoints,
            Feature::MAX_ACCELERATION => $this->maxAcceleration,
            Feature::MAX_VELOCITY => $this->maxVelocity,
            Feature::STOP_RATE => $this->stopCounter / $this->distance,
            'time' => $this->time,
            'distance' => $this->distance,
            'startPoint' => $this->gpsTrackPoints[0],
            'endPoint' => $this->gpsTrackPoints[$this->amountOfTrackPoints],
            'trackPoints' => $this->gpsTrackPoints,
            'type' => $type,
        ];
    }

    /**
     * Handle velocity
     *
     * @param $currentVelocity
     */
    protected function handleVelocity($currentVelocity)
    {
        $this->totalVelocity += $currentVelocity;
        if ($currentVelocity > $this->maxVelocity) {
            $this->maxVelocity = $currentVelocity;
        }
    }

    /**
     * Handle acceleration
     *
     * @param $currentAcceleration
     */
    protected function handleAcceleration($currentAcceleration)
    {
        if ($currentAcceleration > $this->maxAcceleration) {
            $this->maxAcceleration = $currentAcceleration;
        }
        if ($currentAcceleration > 0) {
            $this->totalAcceleration += $currentAcceleration;
            $this->accTrackPoints++;
        }
        $this->prevAcceleration = $currentAcceleration;
    }

    /**
     * Determines and handles possible stops
     * Bilijecki counts the points with speed below a certain value
     * when a certain amount of points is below a velocity threshold it
     * counts as a stop
     *
     * @param $currentVelocity
     * @param $currentTime
     */
    protected function handlePossibleStop($currentVelocity, $currentTime)
    {

        if ($currentVelocity < $this->maxVelocityForNearlyStopPoints) {
            $this->lowSpeedTimeCounter += $currentTime;

            if ($this->lowSpeedTimeCounter >= $this->maxTimeWithoutMovement) {
                $this->stopCounter++;
                $this->lowSpeedTimeCounter = 0;
            }
        } else {
            $this->lowSpeedTimeCounter = 0;
        }
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

    /**
     * Handles data in case it contains a track model
     *
     * @param $data
     *
     * @throws InvalidArgumentException
     */
    protected function handleTrackData($data)
    {
        /** @var TrackInterface $track */
        $track = $data['track'];
        foreach ($track->getSegments() as $segment) {
            $features = $this->getSegmentFeatures($segment->getTrackPoints(), $segment->getType());
            $segment->setFeatures($features);
        }
        $this->write($data['track']);
    }

    /**
     * Handles data in case it contains only trackpoints (e.g. training data)
     *
     * @param $data
     *
     * @throws InvalidArgumentException
     */
    protected function handleTrackpointsData($data)
    {
        if (!array_key_exists('trackPoints', $data) || !array_key_exists('analyseType', $data)) {
            throw new InvalidArgumentException(
                'SegmentFilter: Data param should contain trackpoints or a track with the analyse type!'
            );
        }

        $features = $this->getSegmentFeatures($data['trackPoints'], $data['type']);
        $segment = $this->createSegment($features);
        $this->write(
            [
                'segment' => $segment,
                'analyseType' => $data['analyseType'],
            ]
        );
    }
}
