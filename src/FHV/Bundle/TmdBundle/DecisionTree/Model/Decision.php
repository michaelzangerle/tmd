<?php

namespace FHV\Bundle\TmdBundle\DecisionTree\Model;

use FHV\Bundle\TmdBundle\DecisionTree\Exception\InvalidFieldException;
use FHV\Bundle\TmdBundle\DecisionTree\Exception\InvalidComparatorException;

/**
 * A decision of a decision tree
 * Class Decision
 * @package FHV\Bundle\TmdBundle\DecisionTree\Model
 */
class Decision
{
    const GT_OPERATOR = '>';
    const GTEQ_OPERATOR = '>=';
    const LT_OPERATOR = '<';
    const LTEQ_OPERATOR = '<=';
    const EQ_OPERATOR = '===';

    /**
     * @var string
     */
    protected $field;

    /**
     * @var int
     */
    protected $operator;

    /**
     * @var
     */
    protected $value;

    function __construct($field, $operator, $value)
    {
        $this->field = $field;
        $this->operator = $this->validateOperator($operator);
        $this->value = $value;
    }

    /**
     * Checks if a decision results in true or false
     *
     * @param array $values
     *
     * @return bool
     * @throws InvalidFieldException
     */
    public function applyDecision(array $values)
    {

        if (array_key_exists($this->field, $values)) {
            switch ($this->operator) {
                case Decision::GT_OPERATOR:
                    return $values[$this->field] > $this->value;
                case Decision::GTEQ_OPERATOR:
                    return $values[$this->field] >= $this->value;
                case Decision::LT_OPERATOR:
                    return $values[$this->field] < $this->value;
                case Decision::LTEQ_OPERATOR:
                    return $values[$this->field] <= $this->value;
                case Decision::EQ_OPERATOR:
                    return $values[$this->field] === $this->value;
            }
        } else {
            throw new InvalidFieldException($this->field);
        }
    }

    /**
     * Validates an operator
     * Returns it if its valid or throws an expection
     *
     * @param $operator
     *
     * @return mixed
     * @throws InvalidComparatorException
     */
    protected function validateOperator($operator)
    {
        switch ($operator) {
            case Decision::GT_OPERATOR:
            case Decision::GTEQ_OPERATOR:
            case Decision::LT_OPERATOR:
            case Decision::LTEQ_OPERATOR:
            case Decision::EQ_OPERATOR:
                return $operator;
            default:
                throw new InvalidComparatorException($operator);
        }
    }
}
