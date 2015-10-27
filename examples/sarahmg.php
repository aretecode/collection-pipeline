<?php

require_once __DIR__.'/../vendor/autoload.php';

class Owner {
    public $id;
    public $state;
    public function __construct($id, $state) {
        $this->id = $id;
        $this->state = $state;
    }
    
    public function isConfirmed() {
        return $this->state;
    }
}

class Testing {
    public $owner;
    
    public function __construct($owner) {
        $this->owner = $owner;
    }
    
    public function getOwner() {
        return $this->owner;
    } 
}

$array = [
    new Testing(new Owner(1, true)),
    new Testing(new Owner(2, false))
];

// this...
$array = array_map(function($x) { return $x->getOwner(); }, $array);
$filtered = array_filter($array, function($x) { return $x->isConfirmed(); } );
$count = count($array);

// is the same as this
$count = cp($array)->mapWheres('getOwner')->wheres('isConfirmed')->count();

// which is the same as this
$count = cp($array)->getOwner('map')->isConfirmed()->count();
