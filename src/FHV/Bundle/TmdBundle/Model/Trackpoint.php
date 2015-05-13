<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a trackpoint
 * Class TrackPoint
 * @package FHV\Bundle\TmdBundle\Model
 */
class Trackpoint implements TrackpointInterface
{
    /**
     * @var float
     */
    protected $ele;

    /**
     * @var float
     */
    protected $lat;

    /**
     * @var float
     */
    protected $long;

    /**
     * @var \DateTime
     */
    protected $time;

    function __construct(\SimpleXMLElement $xml)
    {
        if ($this->isValidPointXML($xml)) {
            $xml = (array)$xml;
            $this->ele = floatval($xml['ele']);
            $this->time = new \DateTime($xml['time']);
            $this->lat = floatval($xml['@attributes']['lat']);
            $this->long = floatval($xml['@attributes']['lon']);
        } else {
            throw new \InvalidArgumentException('The provided xml for a trackpoint (' . $xml->time . ') is invalid!');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEle()
    {
        return $this->ele;
    }

    /**
     * {@inheritdoc}
     */
    public function setEle($ele)
    {
        $this->ele = $ele;
    }

    /**
     * {@inheritdoc}
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * {@inheritdoc}
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * {@inheritdoc}
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * {@inheritdoc}
     */
    public function setLong($long)
    {
        $this->long = $long;
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
     * Validates the xml data received via the constructor
     *
     * @param $xml
     *
     * @return bool
     */
    protected function isValidPointXML($xml)
    {
        if ($xml->ele && $xml->time && $xml->attributes()->lat && $xml->attributes()->lon) {
            return true;
        }

        return false;
    }
}
