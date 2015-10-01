<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\AbstractExpressionBuilder;

/**
 * Modified www.doctrine-project.org ExpressionBuilder
 */
class ExpressionBuilder extends AbstractExpressionBuilder
{
    const SPACE = '<=>';
    
    /**
     * {@inheritDoc}
     */
    public function comparison($x, $operator, $y)
    {
        switch ($operator) {
            case self::EQ:
                return $x == $y;

            case self::SAME:
                return $x === $y;

            case self::NEQEQ:
                return $x !== $y;

            case self::NEQ:
                return $x != $y;

            case self::LT:
                return $x < $y;

            case self::LTE:
                return $x <= $y;

            case self::GT:
                return $x > $y;

            case self::GTE:
                return $x >= $y;

            case self::NEQGL:
                return $x <> $y;

            case self::SPACE:
                return $x <=> $y;

            default:
                throw new InvalidArgumentException('operator did not match any case, it was `' . $operator . '`');
        }
    }
}