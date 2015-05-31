<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a track segment
 * Class Tracksegment
 * @package FHV\Bundle\TmdBundle\Model
 */
class Tracksegment implements TracksegmentInterface
{
    /**
     * Array with scalar features
     * Contains basic features like distance and time and
     * depending on the analysis type different other
     * features
     * @var array
     */
    protected $features = [];

    /**
     * @var TrackpointInterface[]
     */
    protected $trackpoints = [];

    /**
     * @var TrackpointInterface
     */
    protected $startPoint;

    /**
     * @var TrackpointInterface
     */
    protected $endPoint;

    /**
     * @var Result
     */
    protected $result;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $time;

    /**
     * @var float
     */
    protected $distance;

    /**
     * @param                       $time
     * @param float                 $distance in meters
     * @param TrackpointInterface   $startPoint
     * @param TrackpointInterface   $endPoint
     * @param TrackpointInterface[] $trackPoints
     * @param string                $type of segment
     */
    function __construct(
        $time = 0,
        $distance = 0.0,
        $startPoint = null,
        $endPoint = null,
        $trackPoints = [],
        $type = 'undefined'
    ) {
        $this->time = $time;
        $this->distance = round($distance, 2);
        $this->type = $type;
        $this->endPoint = $endPoint;
        $this->startPoint = $startPoint;
        $this->trackpoints = $trackPoints;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeature($key, $value)
    {
        if(is_float($value)){
            $value = round($value, 16);
        }
        $this->features[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeature($key)
    {
        if (array_key_exists($key, $this->features)) {
            return $this->features[$key];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function addTrackPoint(TrackpointInterface $trackPoint)
    {
        $this->trackpoints[] = $trackPoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrackPoints()
    {
        return $this->trackpoints;
    }

    /**
     * {@inheritdoc}
     */
    public function setTrackPoints($trackPoints)
    {
        $this->trackpoints;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartPoint($startPoint)
    {
        $this->startPoint = $startPoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndPoint()
    {
        return $this->endPoint;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * {@inheritdoc}
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * {@inheritdoc}
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * {@inheritdoc}
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * {@inheritdoc}
     */
    public function addTrackPoints(array $tps)
    {
        $this->trackpoints = array_merge($this->trackpoints, $tps);
    }

    /**
     * Sets features for a segment but takes also care of basic features like
     * distance, time, start, end, type and trackpoints which will set separately
     * @param array $features
     */
    public function setFeatures(array $features)
    {
        foreach ($features as $key => $feature) {
            switch ($key) {
                case 'time':
                    $this->setTime($feature);
                    break;
                case 'distance':
                    $this->setDistance($feature);
                    break;
                case 'trackPoints':
                    $this->setTrackPoints($feature);
                    break;
                case 'startPoint':
                    $this->setStartPoint($feature);
                    break;
                case 'endPoint':
                    $this->setEndPoint($feature);
                    break;
                case 'type':
                    $this->setType($feature);
                    break;
                default:
                    $this->setFeature($key, $feature);
                    break;
            }
        }
    }
}
