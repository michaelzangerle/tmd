<?php

namespace FHV\Bundle\TmdBundle\Filter;

use FHV\Bundle\PipesAndFiltersBundle\Component\AbstractComponent;
use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\ComponentException;

use FHV\Bundle\PipesAndFiltersBundle\Component\Exception\InvalidArgumentException;
use FHV\Bundle\TmdBundle\Entity\Track as TrackEntity;
use FHV\Bundle\TmdBundle\Entity\Tracksegment as TracksegmentEntity;
use FHV\Bundle\TmdBundle\Entity\Trackpoint as TrackpointEntity;
use FHV\Bundle\TmdBundle\Entity\Feature as FeatureEntity;
use FHV\Bundle\TmdBundle\Entity\Result as ResultEntity;

use FHV\Bundle\TmdBundle\Model\Track as TrackModel;
use FHV\Bundle\TmdBundle\Model\Tracksegment as TracksegmentModel;
use FHV\Bundle\TmdBundle\Model\Trackpoint as TrackpointModel;
use FHV\Bundle\TmdBundle\Model\Result as ResultModel;

/**
 * A sink which persists the given track data to the database
 * Class DatabaseWriter
 * @package FHV\Bundle\TmdBundle\Filter
 */
class DatabaseWriter extends AbstractComponent implements DatabaseWriterInterface
{
    /**
     * @var TrackEntity
     */
    protected $track;

    function __construct()
    {
        parent::__construct();
        $this->track = new TrackEntity();
    }

    /**
     * Starts a filter and processes the given data
     *
     * @param $data
     *
     * @throws ComponentException
     */
    public function run($data)
    {
        if ($data !== null && $data instanceof TrackModel) {
            $this->generateTrackEntityFromModel($data);
        } else {
            throw new InvalidArgumentException(
                'DatabaseFilter: Data param should be instance of Track!'
            );
        }
    }

    /**
     * Provide a track object
     *
     * @param TrackEntity $track
     */
    public function provideTrack(TrackEntity $track)
    {
        $this->track = $track;
    }

    /**
     * Generates a track entity with all its related entities from a track model
     *
     * @param TrackModel $tm
     */
    protected function generateTrackEntityFromModel(TrackModel $tm)
    {
        $this->track->setAnalyseType($tm->getAnalysisType());
        foreach ($tm->getSegments() as $segModel) {
            $this->generateTrackSegementEntityFromModel($segModel);
        }
    }

    /**
     * Generates a segment entity and all its associated entities and connects itself
     *
     * @param TracksegmentModel $sm
     *
     * @return TracksegmentEntity
     */
    protected function generateTrackSegementEntityFromModel(TracksegmentModel $sm)
    {
        $segment = new TracksegmentEntity();
        $segment->setDistance($sm->getDistance());
        $segment->setTime($sm->getTime());
        $segment->setTrack($this->track);
        $segment->setResult($this->generateResultEntityFromModel($sm->getResult(), $segment));
        $this->track->addSegment($segment);

        foreach ($sm->getFeatures() as $key => $value) {
            $this->generateFeatureEntityFromModel($key, $value, $segment);
        }

        /** @var TrackpointModel $tp */
        foreach ($sm->getTrackPoints() as $tp) {
            $this->generateTrackpointEntityFromModel($tp, $segment);
        }

        return $segment;
    }

    /**
     * Creates a track pont entity
     *
     * @param TrackpointModel $tpm
     * @param TracksegmentEntity $segment
     *
     * @return TrackpointEntity
     */
    protected function generateTrackpointEntityFromModel(TrackpointModel $tpm, TracksegmentEntity $segment)
    {
        $trackpoint = new TrackpointEntity();
        $trackpoint->setLatitude($tpm->getLat());
        $trackpoint->setLongitude($tpm->getLong());
        $trackpoint->setTime($tpm->getTime());
        $trackpoint->setSegment($segment);
        $segment->addTrackpoint($trackpoint);

        return $trackpoint;
    }

    /**
     * @param string $key
     * @param                    $value
     * @param TracksegmentEntity $segment
     *
     * @return FeatureEntity
     */
    protected function generateFeatureEntityFromModel($key, $value, TracksegmentEntity $segment)
    {
        $feature = new FeatureEntity();
        $feature->setDescription($key);
        $feature->setValue($value);
        $feature->setSegment($segment);
        $segment->addFeature($feature);

        return $feature;
    }

    /**
     * @param ResultModel $rm
     * @param TracksegmentEntity $segment
     *
     * @return ResultEntity
     */
    protected function generateResultEntityFromModel(ResultModel $rm, TracksegmentEntity $segment)
    {
        $result = new ResultEntity();
        $result->setSegment($segment);
        $result->setAnalyseType($rm->getAnalizationType());
        $result->setTransportType($rm->getTransportType());
        $result->setProbability($rm->getProbability());
        $segment->setResult($result);

        return $result;
    }
}
