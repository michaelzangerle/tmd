<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\TmdBundle\Entity\GISCoordinate;
use FHV\Bundle\TmdBundle\Entity\GISCoordinateRepository;
use FHV\Bundle\TmdBundle\Model\Feature;
use FHV\Bundle\TmdBundle\Model\TrackpointInterface;
use FHV\Bundle\TmdBundle\Util\TrackpointUtil;

/**
 * Adds next to other information also gis data to segments
 * Class GISTracksegmentFilter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class GISTracksegmentFilter extends TracksegmentFilter
{
    /**
     * Variables needed for the gis features
     */
    protected $publicTransportStationCounter;
    protected $lowSpeedTrackPoints;
    protected $infrastructureTimer;
    protected $railCounter;
    protected $highwayCounter;
    protected $pts;

    /**
     * @var GISCoordinateRepository
     */
    protected $gisCoordinateRepository;

    /**
     * @var array
     */
    protected $gisAnalyseConfig;

    function __construct(
        TrackpointUtil $util,
        $minTrackPointsPerSegment,
        $maxVelocityForNearlyStopPoints,
        $maxTimeWithoutMovement,
        GISCoordinateRepository $gisCoordinateRepo,
        array $gisAnalyseConfig
    )
    {
        parent::__construct($util, $minTrackPointsPerSegment, $maxVelocityForNearlyStopPoints, $maxTimeWithoutMovement);
        $this->gisCoordinateRepository = $gisCoordinateRepo;
        $this->gisAnalyseConfig = $gisAnalyseConfig['gis']['config']; // todo better way?
    }

    /**
     * Resets all the variables needed for segment features
     */
    protected function resetSegmentValues()
    {
        parent::resetSegmentValues();
        $this->publicTransportStationCounter = 0;
        $this->lowSpeedTrackPoints = [];
        $this->infrastructureTimer = 0;
        $this->railCounter = 0;
        $this->highwayCounter = 0;
        $this->pts = 0;
    }

    /**
     * Returns the result for the current segment
     * @param $type
     * @return array
     */
    protected function getResultForCurrentSegment($type)
    {
        $result = parent::getResultForCurrentSegment($type);
        $result[FEATURE::PUBLIC_TRANSPORT_STATION_CLOSENESS] = $this->pts;
        $result[FEATURE::HIGHWAY_CLOSENESS] = $this->highwayCounter / $this->time;
        $result[FEATURE::RAIL_CLOSENESS] = $this->railCounter / $this->time;
        
        return $result;
    }

    /**
     * Special handling for the first trackpoint of a segment
     * @param TrackpointInterface $tp
     */
    protected function firstTrackpointHandling(TrackpointInterface $tp)
    {
        parent::firstTrackpointHandling($tp);
        // TODO check infrastructure for first point
    }

    /**
     * Special handling for the last trackpoint of segment
     * @param TrackpointInterface $tp
     */
    protected function lastTrackpointHandling(TrackpointInterface $tp)
    {
        parent::lastTrackpointHandling($tp);
        // TODO check infrastructure for last point

        $this->pts = $this->stopCounter > 0 ? $this->publicTransportStationCounter / $this->stopCounter : 0;
    }

    /**
     * Handle values for different features
     * @param $currentVelocity
     * @param $currentAcceleration
     * @param $currentTime
     */
    protected function handleFeatures($currentVelocity, $currentAcceleration, $currentTime)
    {
        $this->handleVelocity($currentVelocity);
        $this->handleAcceleration($currentAcceleration);
        $this->handlePossibleStop($currentVelocity, $currentTime);
        $this->handleInfrastructure($this->currentTp);
    }

    /**
     * Handle infrastructure features
     * @param TrackpointInterface $tp
     */
    protected function handleInfrastructure(TrackpointInterface $tp)
    {
        // on rails or highway
        if ($this->infrastructureTimer >= $this->gisAnalyseConfig['infrastructureTimeThreshold']) {
            $this->infrastructureTimer = 0;
            if ($this->tpOnInfrastructure($tp, GISCoordinate::RAILWAY_TYPE)) {
                $this->railCounter++;
            }
            if ($this->tpOnInfrastructure($tp, GISCoordinate::HIGHWAY_TYPE)) {
                $this->highwayCounter++;
            }
        }
    }

    /**
     * Determines and handles possible stops
     * @param $currentVelocity
     * @param $currentTime
     */
    protected function handlePossibleStop($currentVelocity, $currentTime)
    {
        // bilijecki counts the points with speed below a certain value
        // when a certain amount is below a the velocity threshold it
        // counts as a stop
        if ($currentVelocity < $this->maxVelocityForNearlyStopPoints) {
            $this->lowSpeedTimeCounter += $currentTime;
            $this->lowSpeedTrackPoints[] = $this->currentTp;

            if ($this->lowSpeedTimeCounter >= $this->maxTimeWithoutMovement) {
                $this->stopCounter++;
                $this->lowSpeedTimeCounter = 0;

                // is a busstop/trainstation nearby the last x tps
                if ($this->isPublicTransportStationNearby($this->lowSpeedTrackPoints)) {
                    $this->publicTransportStationCounter++;
                    $this->lowSpeedTrackPoints = [];
                }
            }
        } else {
            $this->lowSpeedTimeCounter = 0;
            $this->lowSpeedTrackPoints = [];
        }
    }

    /**
     * Checks if a public transport station was nearby during the last x trackpoints
     * @param TrackpointInterface[] $trackPoints
     * @return bool
     */
    protected function isPublicTransportStationNearby(array $trackPoints)
    {
        $trackPoint = $this->getElementInMid($trackPoints);
        $boundingBox = $this->util->getBoundingBox($trackPoint, $this->gisAnalyseConfig['boundingBoxDistance']);
        $coordinates = $this->gisCoordinateRepository->getCoordinatesForBoundingBox(
            $boundingBox,
            GISCoordinate::BUSSTOP_TYPE
        );

        if ($coordinates) {
            return true;
        }

        return false;
    }

    /**
     * Returns the
     * @param TrackpointInterface[] $trackPoints
     * @return TrackpointInterface
     * @throws FilterException
     */
    protected function getElementInMid(array $trackPoints)
    {
        if ($trackPoints && count($trackPoints) > 0) {
            $length = count($trackPoints);
            switch ($length) {
                case 1:
                    return $trackPoints[0];
                case 2:
                    return $trackPoints[1];
                default:
                    $idx = ceil($length / 2);
                    return $trackPoints[$idx];
            }
        }

        throw new FilterException('The parameters provided to get trackpoint in mid are invalid!');
    }

    /**
     * Checks if the given track point could be on rails
     * @param TrackpointInterface $tp
     * @param string $type
     * @return bool
     */
    protected function tpOnInfrastructure(TrackpointInterface $tp, $type)
    {
        $boundingBox = $this->util->getBoundingBox($tp, $this->gisAnalyseConfig['infrastructureDistanceThreshold']);
        $result = $this->gisCoordinateRepository->getCoordinatesForBoundingBox($boundingBox, $type);
        if ($result) {
            return true;
        }
        return false;
    }
}
