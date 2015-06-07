<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Model;

use FHV\Bundle\TmdBundle\Model\TracksegmentType;

/**
 * A leaf of a decision tree which represents a result
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

    function __construct(array $values)
    {
        $this->values = [
            TracksegmentType::BIKE => $values[TracksegmentType::BIKE],
            TracksegmentType::WALK => $values[TracksegmentType::WALK],
            TracksegmentType::DRIVE => $values[TracksegmentType::DRIVE],
            TracksegmentType::BUS => $values[TracksegmentType::BUS],
            TracksegmentType::TRAIN => $values[TracksegmentType::TRAIN],
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
