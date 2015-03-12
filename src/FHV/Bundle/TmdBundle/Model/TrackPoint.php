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
     * @return mixed
     */
    public function getEle()
    {
        return $this->ele;
    }

    /**
     * @param mixed $ele
     */
    public function setEle($ele)
    {
        $this->ele = $ele;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * @param mixed $long
     */
    public function setLong($long)
    {
        $this->long = $long;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    private function isValidPointXML($xml)
    {
        if ($xml->ele && $xml->time && $xml->attributes()->lat && $xml->attributes()->lon) {
            return true;
        }

        return false;
    }
}
