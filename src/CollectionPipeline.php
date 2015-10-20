<?php

namespace Arete\CollectionPipeline;

use Arete\Support\Arr;
use Illuminate\Support\Collection as LaravelCollection;

class CollectionPipeline extends LaravelCollection {
    /**
     * http://stackoverflow.com/questions/3797239/insert-new-item-in-array-on-any-position-in-php
     * http://binarykitten.com/php/52-php-insert-element-and-shift.html
     * now returning the state so we can keep trying to insert before the matching
     */
    public function insertBeforeMatching($specification, $element) {
        $this->items = Arr::insertAfterMatching($this->items, $specification, $element);
        return $this;
    }
    public function insertBeforeKey($key, $element) {
        $this->items = Arr::insertBeforeKey($this->items, $key, $element);
        return $this;
    }
    public function insertAfterKey($afterKey, $key, $element) {
        $this->items = Arr::insertAfterKey($this->items, $afterKey, $key, $element);
        return $this;
    }
    public function insertAfterMatching($specification, $value) {
        return Arr::insertAfterMatching($this->items, $specification, $value);
    }

    public function removeSatisfying($specification) {
        $this->items = Arr::removeSatisfying($this->items, $specification);
    }

    public function wheresKey($condition, $value = null, $order = null) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheresKey($condition, $value, $order)
            ->process($this->items);

        return self::from($extendedPipeline);
    }
    
    public function wheresEach($condition, $value = null, $type = ['method', 'property', 'callable', 'index']) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheres(null, $condition, $value, $type)
            ->process($this->items);

        return self::from($extendedPipeline);
    }

    public function wheres($accessor, $condition, $value = null, $type = ['method', 'property', 'callable', 'index'], $order = null) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheres($accessor, $condition, $value, $type, $order)
            ->process($this->items);

        return self::from($extendedPipeline);
    }

    ##############
    public function wheresYX($accessor, $condition, $value, $type = ['method', 'property', 'callable', 'index']) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheresYX($accessor, $condition, $value, $type)
            ->process($this->items);

        return self::from($extendedPipeline);
    }
    public function wheresXY($accessor, $condition, $value, $type = ['method', 'property', 'callable', 'index']) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheresXY($accessor, $condition, $value, $type)
            ->process($this->items);

        return self::from($extendedPipeline);
    }
    ##############
    public function satisfying($specification) {
        $extendedPipeline = (new ExtendedPipeline)
            ->satisfying($specification)
            ->process($this->items);

        return self::from($extendedPipeline);
    }

    public function pushAll($array) {
        foreach ($array as $item)
            $this->push($item);
    }

    public static function from($array) {
        return new self($array);
    }
}
