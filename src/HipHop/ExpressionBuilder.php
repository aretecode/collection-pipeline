<?php

namespace Arete\CollectionPipeline;

use InvalidArgumentException;
use Arete\CollectionPipeline\AbstractExpressionBuilder;

/**
 * Modified www.doctrine-project.org ExpressionBuilder
 */
class ExpressionBuilder extends AbstractExpressionBuilder {
    const SPACE = '<=>';
    
    /**
     * {@inheritDoc}
     */
    public function comparison($x, $operator, $y) {
        if ($operator === self::SPACE) 
            return $x <=> $y;
        return parent::comparison($x, $operator, $y);
    }
}