<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Model;

use FHV\Bundle\TmdBundle\Model\TracksegmentType;

/**
 * Class Result
 * @package FHV\Bundle\TmdBundle\DecisionTree\Model
 */
class Result
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @var string
     */
    protected $maxName;

    /**
     * @var float
     */
    protected $maxValue = 0;

    /**
     * @var array
     */
    protected $values;

    function __construct($bike, $walk, $car, $bus, $train)
    {
        $this->values = [
            TracksegmentType::BIKE => $bike,
            TracksegmentType::WALK => $walk,
            TracksegmentType::DRIVE => $car,
            TracksegmentType::BUS => $bus,
            TracksegmentType::TRAIN => $train
        ];

        foreach ($this->values as $key => $nr) {
            $this->total += $nr;
            if ($nr > $this->maxValue) {
                $this->maxValue = $nr;
                $this->maxName = $key;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getMaxName()
    {
        return $this->maxName;
    }

    /**
     * @return mixed
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }
}
