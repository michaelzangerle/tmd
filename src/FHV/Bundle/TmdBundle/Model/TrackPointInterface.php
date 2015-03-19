<?php

namespace FHV\Bundle\TmdBundle\Model;


interface TrackPointInterface
{
    /**
     * @return float
     */
    public function getEle();

    /**
     * @param float $ele
     */
    public function setEle($ele);

    /**
     * @return float
     */
    public function getLat();

    /**
     * @param float $lat
     */
    public function setLat($lat);

    /**
     * @return float
     */
    public function getLong();

    /**
     * @param float $long
     */
    public function setLong($long);

    /**
     * @return \DateTime
     */
    public function getTime();

    /**
     * @param \DateTime $time
     */
    public function setTime($time);

}
