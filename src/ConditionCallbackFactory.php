<?php

namespace Arete\CollectionPipeline;

class ConditionCallbackFactory {
    /**
     * usableFunctionOrCallable
     * 
     * @param  string $condition 
     * @return ConditionCallback|bool(false) if !function|callable ($condition)
     */
    public function usableCallback($condition) {     

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
        } else 
            $useNot = false;

        if (is_callable($condition) || (!is_object($condition) && function_exists($condition))) 
            return new ConditionCallback($condition, $useNot);

        return false;
    }
}