<?php

namespace Arete\CollectionPipeline;

use InvalidArgumentException;

/**
 * Modified www.doctrine-project.org ExpressionBuilder
 */
abstract class AbstractExpressionBuilder {
    const EQ  = '=';
    const EQEQEQ = '===';
    const SAME = '===';
    const EQEQ = '==';
    const NEQ = '!=';
    const NEQEQ = '!==';
    const NEQGL = '<>';
    const LT  = '<';
    const LTE = '<=';
    const GT  = '>';
    const GTE = '>=';
    const INSTOF = 'instanceof';
    const NOTINSTOF = '!instanceof';
    const TRUTHY = 'truthy';

    /**
     * Creates a comparison expression.
     *
     * @param mixed  $x        The left expression.
     * @param string $operator One of the ExpressionBuilder::* constants.
     * @param mixed  $y        The right expression.
     *
     * @return bool
     */
    public function comparison($x, $operator, $y) {
        switch ($operator) {
            case self::EQ:
                return $x == $y;

            case self::SAME:
                return $x === $y;

            case self::EQEQ:
                return $x == $y;

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

            case self::INSTOF:
                return $x instanceof $y;

            case self::NOTINSTOF:
                return !($x instanceof $y);

            case self::TRUTHY:
                return ($x) ? true : false;

            case self::NEQGL:
                return $x <> $y;
          
            default:
                throw new InvalidArgumentException('operator did not match any case, it was `' . $operator . '`');
        }
    }
    
    /**
     * Creates an equality comparison expression with the given arguments.
     *
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> = <right expr>. Example:
     *
     *     [php]
     *     // u.id = ?
     *     $expr->eq('u.id', '?');
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function eq($x, $y) {
        return $this->comparison($x, self::EQ, $y);
    }
    /**
     * Creates a non equality comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> <> <right expr>. Example:
     *
     *     [php]
     *     // u.id <> 1
     *     $q->where($q->expr()->neq('u.id', '1'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function neq($x, $y) {
        return $this->comparison($x, self::NEQ, $y);
    }
    /**
     * Creates a lower-than comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> < <right expr>. Example:
     *
     *     [php]
     *     // u.id < ?
     *     $q->where($q->expr()->lt('u.id', '?'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function lt($x, $y) {
        return $this->comparison($x, self::LT, $y);
    }
    /**
     * Creates a lower-than-equal comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> <= <right expr>. Example:
     *
     *     [php]
     *     // u.id <= ?
     *     $q->where($q->expr()->lte('u.id', '?'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function lte($x, $y) {
        return $this->comparison($x, self::LTE, $y);
    }
    /**
     * Creates a greater-than comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> > <right expr>. Example:
     *
     *     [php]
     *     // u.id > ?
     *     $q->where($q->expr()->gt('u.id', '?'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function gt($x, $y) {
        return $this->comparison($x, self::GT, $y);
    }
    /**
     * Creates a greater-than-equal comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> >= <right expr>. Example:
     *
     *     [php]
     *     // u.id >= ?
     *     $q->where($q->expr()->gte('u.id', '?'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function gte($x, $y) {
        return $this->comparison($x, self::GTE, $y);
    }
    /**
     * Creates an IS NULL expression with the given arguments.
     *
     * @param string $x The field in string format to be restricted by IS NULL.
     *
     * @return string
     */
    public function isNull($x) {
        return is_null($x);
    }
    /**
     * Creates an IS NOT NULL expression with the given arguments.
     *
     * @param string $x The field in string format to be restricted by IS NOT NULL.
     *
     * @return string
     */
    public function isNotNull($x) {
        return !is_null($x);
    }
}