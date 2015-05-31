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

    /**
     * @param float $lat
     * @param float $long
     * @param \DateTime $time
     * @param float $ele
     */
    function __construct($lat, $long, $ele = 0.0, $time = null)
    {
        $this->lat = $lat;
        $this->long = $long;
        $this->ele = $ele;
        $this->time = $time;
    }

    /**
     * TODO move into factory?
     * Creates a trackpoint from an xml element
     * @param \SimpleXMLElement $xml
     * @return Trackpoint
     */
    public static function fromXML(\SimpleXMLElement $xml)
    {
        if (self::isValidPointXML($xml)) {
            $xml = (array)$xml;
            $ele = floatval($xml['ele']);
            $time = new \DateTime($xml['time']);
            $lat = floatval($xml['@attributes']['lat']);
            $long = floatval($xml['@attributes']['lon']);

            return new Trackpoint($lat, $long, $ele, $time);
        } else {
            throw new \InvalidArgumentException('The provided xml for a trackpoint (' . $xml->time . ') is invalid!');
        }
    }

    /**
     * Validates the xml data to create a new trackpoint obj
     * TODO move into factory?
     * @param $xml
     *
     * @return bool
     */
    protected static function isValidPointXML($xml)
    {
        if ($xml->ele && $xml->time && $xml->attributes()->lat && $xml->attributes()->lon) {
            return true;
        }

        return false;
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
}
