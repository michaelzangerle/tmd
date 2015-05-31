<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\FilterException;
use FHV\Bundle\PipesAndFiltersBundle\Filter\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Entity\GISCoordinate;
use FHV\Bundle\TmdBundle\Entity\GISCoordinateRepository;
use FHV\Bundle\TmdBundle\Entity\Track;
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
     * Returns a segment for the given trackpoints
     * TODO refactor this function
     *
     * @param TrackpointInterface[] $trackPoints
     * @param string|null $type
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
        $publicTransportStationCounter = 0;
        $lowSpeedTrackPoints = [];
        $infrastructureTimer = 0;
        $railCounter = 0;
        $highwayCounter = 0;

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
                $infrastructureTimer += $currentTime;
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

                // on rails or highway
                if($infrastructureTimer >= $this->gisAnalyseConfig['infrastructureTimeThreshold']){
                    $infrastructureTimer = 0;
                    if($this->tpOnInfrastructure($tp2, GISCoordinate::RAILWAY_TYPE)){
                        $railCounter++;
                    }
                    if($this->tpOnInfrastructure($tp2, GISCoordinate::HIGHWAY_TYPE)){
                        $highwayCounter++;
                    }
                }

                // bilijecki counts the points with speed below a certain value
                // when a certain amount is below a the velocity threshold it
                // counts as a stop
                if ($currentVelocity < $this->maxVelocityForNearlyStopPoints) {
                    $lowSpeedTimeCounter += $currentTime;
                    $lowSpeedTrackPoints[] = $tp2;

                    if ($lowSpeedTimeCounter >= $this->maxTimeWithoutMovement) {
                        $stopCounter++;
                        $lowSpeedTimeCounter = 0;

                        // is a busstop/trainstation nearby the last x tps
                        if ($this->isPublicTransportStationNearby($lowSpeedTrackPoints)) {
                            $publicTransportStationCounter++;
                            $lowSpeedTrackPoints = [];
                        }
                    }
                } else {
                    $lowSpeedTimeCounter = 0;
                    $lowSpeedTrackPoints = [];
                }

                $prevVelocity = $currentAcceleration;
            }

            $pts = $stopCounter > 0 ? $publicTransportStationCounter / $stopCounter : 0;

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
                'type' => $type,
                FEATURE::PUBLIC_TRANSPORT_STATION_CLOSENESS => $pts,
                FEATURE::HIGHWAY_CLOSENESS => $highwayCounter / $time,
                FEATURE::RAIL_CLOSENESS => $railCounter / $time
            ];
        }

        throw new InvalidArgumentException(
            'SegmentFilter: There should at least be ' . $this->minTrackPointsPerSegment . ' trackpoints present!'
        );
    }

    /**
     * Checks if a public transport station was nearby during the last x trackpoints
     * @param TrackpointInterface[] $trackPoints
     * @return bool
     */
    protected function isPublicTransportStationNearby(array $trackPoints)
    {
        $trackPoint = $this->getElementInMid($trackPoints);
        $boundingBox = $this->util->getBoundingBox($trackPoint, $this->gisAnalyseConfig['boundingBoxDistance']); // TODO add to config
        $coordinates = $this->gisCoordinateRepository->getCoordinatesForBoundingBox($boundingBox, GISCoordinate::BUSSTOP_TYPE);
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

        throw new FilterException('The parameters provided to get trackpoint in mid!');
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
        if($result){
            return true;
        }
        return false;
    }
}
