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
    }
    public function insertBeforeKey($key, $element) {
        $this->items = Arr::insertBeforeKey($this->items, $key, $element);
    }
    public function insertAfterKey($afterKey, $key, $value) {
        $this->items = Arr::insertAfterKey($this->items, $afterKey, $key, $element);
    }
    public function insertAfterMatching($specification, $value) {
        return Arr::insertAfterMatching($this->items, $specification, $value);
    }

    public function removeSatisfying($specification) {
        $this->items = Arr::removeSatisfying($this->items, $specification);
    }

    public function wheres($function, $condition, $value = null, $type = ['method', 'property', 'callable']) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheres($function, $condition, $value, $type = ['method', 'property', 'callable'])
            ->process($this->items);

        return self::from($extendedPipeline);
    }

    ##############
    public function wheresYX($function, $condition, $value, $type = ['method', 'property', 'callable']) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheresYX($function, $condition, $value, $type = ['method', 'property', 'callable'])
            ->process($this->items);

        return self::from($extendedPipeline);
    }
    public function wheresXY($function, $condition, $value, $type = ['method', 'property', 'callable']) {
        $extendedPipeline = (new ExtendedPipeline)
            ->wheresXY($function, $condition, $value, $type = ['method', 'property', 'callable'])
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
