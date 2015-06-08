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
     * Returns the total number of results in this case
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns the name of the maximum type
     *
     * @return string
     */
    public function getMaxName()
    {
        return $this->maxName;
    }

    /**
     * Returns the value of the maximum type
     *
     * @return float
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }
}
