<?php

namespace Arete\CollectionPipeline;

use League\Pipeline\Pipeline;
use League\Pipeline\PipelineBuilder;
use League\Pipeline\CallableStage;
use League\Pipeline\StageInterface;

// use SymfonyPropertyAccessor?
// call_user_func_array
// class HasNotOperatorSpecification
/*
1. WHAT ABOUT CALLING A :: __METHOD__ ON THE $PAYLOADVALUE THAT TAKES IN AN ARGUMENT LIKE THE $Y/$VALUE?
use call_user_func_array??

2. What about doing like

Could use `WhereEach`?

2.1 CP::from($arr)->where('==', $otherObject)

2.2 CP::from($arr)->whereEach('is_object')

2.2 CP::from($arr)->whereXY('in_array', $array)
*/

/**
 * ExtendedPipelineDecorator
 */
class ExtendedPipeline extends Pipeline {

    /**
     * Constructor.
     *
     * @param StageInterface[] $stages
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $stages = []) {
        parent::__construct($stages);

        // could DI in the Builder
        $this->callbackFactory = new ConditionCallbackFactory();
        $this->accessor = new Accessor();
        $this->expression = new ExpressionBuilder();
    }

    /**
     * @param  string|callable  $condition
     * @param  mixed            $value
     * @return ExtendedPipeline
     */
    public function wheresEach($condition, $value = null) {
        return $this->pipe(new CallableStage(function ($payload) use ($accessor, $condition, $value) {
            foreach ((array)$payload as $key => $payloadValue)
                $payload = $this->removePayloadIfNeeded($key, $payload, $condition, $value, $payloadValue);

            return $payload;
        }));
    }

    public function satisfying($specification) {
        return $this->pipe(new CallableStage(function ($payload) use ($specification) {
            foreach ((array)$payload as $key => $payloadValue)
                if (!$specification->isSatisfiedBy($payloadValue))
                    unset($payload[$key]);

            return $payload;
        }));
    }

    /**
     * [ ] @TODO: some sort of plugin to be used to loop through when removing things that do not match, could be a callable?
     *
     * Example:
     *     -
     *         $accessor = 'value';
     *         $condition = '>';
     *         $value = 'The Grinch';
     *         (optional) $types = ['method', 'property', 'callable', 'index', 'key'] # checks method first, property second, callable third
     *         (assuming the $payload was {protected $value = 'Susie';} # where {} = short object syntax)
     *         would only get the things that had 'The Grinch' as a value, none in this case.
     *
     * @throws \InvalidArgumentException
     *         if $value = null and $condition = 1) not function, 2) not callable
     *
     * @param  string           $accessor
     * @param  string|callable  $condition
     * @param  mixed            $value
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', 'callable', 'index', or 'key' in ltr order
     * @param  string|null      [$order] (optional) 'xy', 'yx', or null
     * @return ExtendedPipeline
     */
    public function wheres($accessor, $condition, $value = null, $types = ['method', 'property', 'callable', 'index', 'key'], $order = null) {
        return $this->pipe(new CallableStage(function ($payload) use ($accessor, $condition, $value, $types, $order) {

            foreach ((array)$payload as $key => $payloadValue) {
                $x = $this->accessor->usingType($accessor, $key, $payloadValue, $types);
                $payload = $this->removePayloadIfNeeded($key, $payload, $condition, $value, $x, $order);
            }

            return $payload;
        }));
    }
    /**
     * @param  string|callable  $condition
     * @param  string           $value
     * @param  string|null      [$order] (optional) 'xy', 'yx', or null
     * @return ExtendedPipeline
     */
    public function wheresKey($condition, $value, $order = null) {
        return $this->pipe(new CallableStage(function ($payload) use ($condition, $value, $order) {
            foreach (array_keys((array)$payload) as $key)
                $payload = $this->removePayloadIfNeeded($key, $payload, $condition, $value, $key, $order);

            return $payload;
        }));
    }

    ###############################################################################################################
    /**
     * @param  string           $accessor
     * @param  string|callable  $condition
     * @param  string           $value
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', 'callable', 'index', or 'key'  in ltr order
     * @return ExtendedPipeline
     */
    public function wheresXY($accessor, $condition, $value, $types = ['method', 'property', 'callable', 'index', 'key']) {
        return $this->wheres($accessor, $condition, $value, $types, 'xy');
    }
    /**
     * @param  string           $accessor
     * @param  string|callable  $condition
     * @param  string           $value
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', 'callable', 'index', or 'key' in ltr order
     * @return ExtendedPipeline
     */
    public function wheresYX($accessor, $condition, $value, $types = ['method', 'property', 'callable', 'index', 'key']) {
        return $this->wheres($accessor, $condition, $value, $types, 'yx');
    }

    /**
     * @param  array            $payload
     * @param  bool             $useNot
     * @param  string|callable  $condition
     * @param  mixed            $x                  result from ::accessor
     * @param  mixed            [$y]     (optional) $value being compared
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', or 'callable' in ltr order
     * @return bool
     */
    protected function shouldRemove($condition, $x, $y = null, $order = null) {
        $result = $this->callbackFactory->usableCallback($condition);

        if (!$result)  // we are removing the ones we do `not` want
            return !$this->expressionOrderComparison($condition, $x, $y, $order);

        $comparison = $this->argsOrderComparison($result->getCallback(), $x, $y, $order);
        if ($result->usesNotOperator())
            return !!$comparison; // double `not` if `useNot`

        // we are removing the ones we do `not` want
        return !$comparison;
    }

    /**
     * @param  string|callable  $condition
     * @param  mixed            $x                  result from ::accessor || $payloadValue
     * @param  mixed            $y                  $value being compared
     * @param  string|null      [$order] (optional) 'xy', 'yx', or null
     * @return bool
     */
    protected function expressionOrderComparison($condition, $x, $y, $order = null) {
        if ($order == 'yx') 
            return $this->expression->comparison($y, $condition, $x);

        return $this->expression->comparison($x, $condition, $y);
    }

    /**
     * @param  string|callable  $condition
     * @param  mixed            $x                  result from ::accessor || $payloadValue
     * @param  mixed            [$y]     (optional) $value being compared
     * @param  string|null      [$order] (optional) 'xy', 'yx', or null
     * @return bool
     */
    protected function argsOrderComparison($condition, $x, $y = null, $order = null) {
        if ($order == 'yx')
            return $condition($y, $x);
        elseif ($order =='xy')
            return $condition($x, $y);

        return $condition($x);
    }
    ###############################################################################################################

    /**
     * Method extraction from ::wheres
     *
     * @throws \InvalidArgumentException
     *         if $value = null and $condition = 1) not function, 2) not callable
     *
     * @param  string               $key
     * @param  array                $payload
     * @param  string|callable      $condition
     * @param  string               $value
     * @param  mixed                $x result from ::accessor || $payloadValue
     * @param  string|null          [$order] (optional) 'xy', 'yx', or null
     * @return array
     */
    protected function removePayloadIfNeeded($key, $payload, $condition, $value, $x, $order = null) {
        // if it should be removed, OR, does *not* match the comparison, remove it
        if ($this->shouldRemove($condition, $x, $value, $order))
            unset($payload[$key]);

        return $payload;
    }
}
