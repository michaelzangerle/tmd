<?php

namespace FHV\Bundle\TmdBundle\Model;

/**
 * Model for a trackpoint
 * Class TrackPoint
 * @package FHV\Bundle\TmdBundle\Model
 */
class TrackPoint
{
    /**
     * @var float
     */
    private $ele;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $long;

    /**
     * @var \DateTime
     */
    private $time;

    function __construct(\SimpleXMLElement $xml)
    {
        if ($this->isValidPointXML($xml)) {
            $xml = (array)$xml;
            $this->ele = floatval($xml['ele']);
            $this->time = new \DateTime($xml['time']);
            $this->lat = floatval($xml['@attributes']['lat']);
            $this->long = floatval($xml['@attributes']['lon']);
        }
    }

    /**
     * @return float
     */
    public function getEle()
    {
        return $this->ele;
    }

    /**
     * @param float $ele
     */
    public function setEle($ele)
    {
        $this->ele = $ele;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * @param float $long
     */
    public function setLong($long)
    {
        $this->long = $long;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Validates the xml data received via the constructor
     * @param $xml
     * @return bool
     */
    private function isValidPointXML($xml)
    {
        if ($xml->ele && $xml->time && $xml->attributes()->lat && $xml->attributes()->lon) {
            return true;
        }

        return false;
    }
}
