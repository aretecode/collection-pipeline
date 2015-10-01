<?php

namespace Arete\CollectionPipeline;

use League\Pipeline\Pipeline;
use League\Pipeline\PipelineBuilder;
use League\Pipeline\CallableStage;
use League\Pipeline\StageInterface;

/**
 *  ExtendedPipelineDecorator
 */
class ExtendedPipeline extends Pipeline {
    /**
     * [ ] @TODO: another argument (optional) whether to check for property or function first
     * [ ] @TODO: whether to use is_callable or not
     * [ ] @TODO: some sort of plugin to be used to loop through when removing things that do not match, could be a callable?
     * 
     * Example:
     *     - 
     *         $functionOrProperty = 'value';
     *         $condition = '>';
     *         $value = 'The Grinch';
     *         (optional) $types = ['method', 'property', 'callable'] # checks method first, property second, callable third
     *         (assuming the $payload was {protected $value = 'Susie';} # where {} = short object syntax) 
     *         would only get the things that had 'The Grinch' as a value, none in this case.
     *         
     * @throws \InvalidArgumentException 
     *         if $value = null and $condition = 1) not function, 2) not callable
     * 
     * @param  string           $functionOrProperty 
     * @param  string|callable  $condition    
     * @param  string           $value            
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', or 'callable' in ltr order     
     * @return ExtendedPipeline
     */
    public function wheres($methodOrProperty, $condition, $value = null, $types = ['method', 'property', 'callable']) {
        // $collection = $this->collection; use ($collection)
        return $this->pipe(new CallableStage(function ($payload) use ($methodOrProperty, $condition, $value, $types) {
            $conditionExpression = new ExpressionBuilder();

            foreach ((array)$payload as $key => $payloadValue) {
                $x = $this->methodOrProperty($methodOrProperty, $payloadValue, $types);
                $payload = $this->wheresComparison($key, $payload, $condition, $value, $x, $conditionExpression);
            }

            return $payload;
        }));
    }

    ###############################################################################################################
    /**
     * @param  string           $functionOrProperty 
     * @param  string|callable  $condition  MUST BE A VALID FUNCTION NAME OR A CALLABLE
     * @param  string           $value            
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', or 'callable' in ltr order     
     * @return ExtendedPipeline
     */
    public function wheresXY($methodOrProperty, $condition, $value, $types = ['method', 'property', 'callable']) {
        return $this->wheres__($methodOrProperty, $condition, $value, $types, 'XY');
    }
    /**
     * @param  string           $functionOrProperty 
     * @param  string|callable  $condition    MUST BE A VALID FUNCTION NAME OR A CALLABLE
     * @param  string           $value            
     * @param  string|array     [$types] (optional) mixture of 'method', 'property', or 'callable' in ltr order     
     * @return ExtendedPipeline
     */
    public function wheresYX($methodOrProperty, $condition, $value, $types = ['method', 'property', 'callable']) {
        return $this->wheres__($methodOrProperty, $condition, $value, $types, 'YX');
    }

    /**
     * just to shorthand it
     */
    protected function wheres__($methodOrProperty, $condition, $value, $types, $order) {
        return $this->pipe(new CallableStage(function ($payload) use ($methodOrProperty, $condition, $value, $types, $order) {
            foreach ((array)$payload as $key => $payloadValue) {
                $x = $this->methodOrProperty($methodOrProperty, $payloadValue, $types);

                $argsOrder = 'argsOrder'.$order;
                $payload = $this->{$argsOrder}($key, $payload, $condition, $value, $x);
            }

            return $payload;
        }));
    }

    /**
     * When we want to use the function with X passed in as the first argument and Y as the second
     */
    protected function argsOrderXY($key, $payload, $condition, $value, $x) {
        $result = $this->usableFunctionOrCallable($condition);

        if ($result['useNot']) {
            $condition = $result['condition'];
            // we are removing the ones we do `not` want, so double `not` if `useNot`
            if (!!$condition($x, $value)) 
                unset($payload[$key]);
        }
        elseif (!$condition($x, $value)) 
            unset($payload[$key]);

        return $payload;
    }
    /**
     * When we want to use the function with Y passed in as the first argument and X as the second
     */
    protected function argsOrderYX($key, $payload, $condition, $value, $x) {
        $result = $this->usableFunctionOrCallable($condition);

        if ($result['useNot']) {
            $condition = $result['condition'];
            // we are removing the ones we do `not` want, so double `not` if `useNot`
            if (!!$condition($value, $x)) {
                unset($payload[$key]);
            }
        }
        elseif (!$condition($value, $x)) {
            unset($payload[$key]);
        }

        return $payload;
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
     * @param  mixed                $x result from ::methodOrProperty      
     * @param  ExpressionBuilder    $conditionExpression     
     * @return array
     */
    protected function wheresComparison($key, $payload, $condition, $value, $x, $conditionExpression) {
        /**
         * [ ] @TODO - HOW CAN THIS NOT BE NULL? 
         *         could pass in an array and check if isset for that position but ehhh
         */
        if (is_null($value)) 
            return $this->wheresComparisonCallable($key, $payload, $condition, $x);

        if ($result = $this->usableFunctionOrCallable($condition))
            return $this->removeFromPayloadIf($key, $payload, $result['useNot'], $result['condition'], $x);

        // if it does *not* match the comparison, remove it
        if (!$conditionExpression->comparison($x, $condition, $value)) 
            unset($payload[$key]);
        
        return $payload;
    }

    /**
     * 
     * 
     * Method extraction from ::wheresComparison
     * 
     * @throws \InvalidArgumentException 
     *         if $condition = 1) not function, 2) not callable
     *         
     * @param  string           $key 
     * @param  array            $payload 
     * @param  string|callable  $condition    
     * @param  mixed            $x result from ::methodOrProperty      
     * @return array
     */
    protected function wheresComparisonCallable($key, $payload, $condition, $x) {
        $usable = $this->usableFunctionOrCallable($condition);
        if (!$usable) 
            throw new \InvalidArgumentException('arguments must have a $value to compare, or a valid function or callable, provided was: `', var_export($condition, true) . '`');
        
        return $this->removeFromPayloadIf($key, $payload, $usable['useNot'], $usable['condition'], $x);
    }

    /**
     * Method extraction of ::wheresComparisonCallable
     * 
     * @param  string $condition 
     * @return array<'useNot' => bool, 'condition' => mixed>|bool(false) if !function|callable ($condition)
     */
    protected function usableFunctionOrCallable($condition) {     
        /** 
         * whether it uses `!` behind the comparison
         * @var boolean
         */
        $useNot = false;

        /**
         * if 
         *     it's a string,
         *     and it has `!` as the first character // aka: substr($condition, 0, 1)
         * then 
         *     we want to remove the `!`,
         *     and set $useNot to true
         *
         *  Example: 
         *   - '!is_string'
         * 
         */
        if (is_string($condition) && $condition[0] == '!') {
            $condition = str_replace('!', "", $condition);
            $useNot = true;
        }

        if (is_callable($condition) || (!is_object($condition) && function_exists($condition))) 
            return ['useNot' => $useNot, 'condition' => $condition];

        return false;
    }

    /**
     * @param  string           $key 
     * @param  array            $payload 
     * @param  bool             $useNot 
     * @param  string|callable  $condition    
     * @param  mixed            $x result from ::methodOrProperty      
     * @return array
     */
    protected function removeFromPayloadIf($key, $payload, $useNot, $condition, $x) {
        if ($useNot) {
            // we are removing the ones we do `not` want, so double `not` if `useNot`
            if (!!$condition($x)) {
                unset($payload[$key]);
            }
        }
        elseif (!$condition($x)) {
            unset($payload[$key]);
        }
        return $payload;
    }

    /**
     * Method extraction from ::whereFunctionOrProperty
     * @param  string        $methodOrProperty 
     * @param  mixed         $payloadValue       
     * @param  string|array  [$types] (optional) mixture of 'method', 'property', or 'callable' in ltr order     
     * @return mixed
     */
    protected function methodOrProperty($methodOrProperty, $payloadValue, $types = ['method', 'property', 'callable']) {        
        foreach ((array)$types as $type) {          
            // echo "methodOrProperty \n<br>"; dump($type);

            if ($type == 'property' && property_exists($payloadValue, $methodOrProperty))
                return $payloadValue->{$methodOrProperty};

            elseif ($type == 'method' && method_exists($payloadValue, $methodOrProperty))
                return $payloadValue->{$methodOrProperty}();

            elseif ($type == 'callable' && is_callable([$payloadValue, $methodOrProperty]))
                return $payloadValue->{$methodOrProperty}();
        }
        
        // if neither does, it is null 
        // [ ] @TODO: add other cases, can check if isset()
        return null;
    }
}
