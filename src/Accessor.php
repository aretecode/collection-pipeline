<?php

namespace Arete\CollectionPipeline;

class Accessor {
    /**
     * @param  string        $accessor 
     * @param  mixed         $payloadKey       
     * @param  mixed         $payloadValue       
     * @param  string|array  [$types] (optional) mixture of 'method', 'property', 'callable', 'index', or 'key' in ltr order     
     * @return mixed
     */
    public function usingType($accessor, $payloadKey, $payloadValue, $types) { 
        foreach ((array)$types as $type) {          
            if ($type == 'property' && property_exists($payloadValue, $accessor))
                return $payloadValue->{$accessor};

            elseif ($type == 'method' && method_exists($payloadValue, $accessor))
                return $payloadValue->{$accessor}();

            elseif ($type == 'callable' && is_callable([$payloadValue, $accessor]))
                return $payloadValue->{$accessor}();

            elseif ($type == 'index' && $accessor === $payloadKey)
                return $payloadValue;

            elseif ($type == 'value' || is_null($accessor))
                return $payloadValue;

            elseif ($type == 'key') 
                return $payloadKey;
        }
        
        // previously returned null, now, just use the PayloadValue
        return null;
    }
}
