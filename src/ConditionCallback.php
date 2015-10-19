<?php

namespace Arete\CollectionPipeline;

class ConditionCallback {
    /**
     * The callback to use 
     * @var callable|string 
     */
    protected $callback;

    /** 
     * whether it uses `!` behind the comparison
     * @var boolean
     */
    protected $notOperator;

    public function __construct($callback, $not = false) {
        $this->callback = $callback;
        $this->notOperator = $not;
    }

    public function getCallback() {
        return $this->callback;
    }

    public function usesNotOperator() {
        return $this->notOperator;
    }
}