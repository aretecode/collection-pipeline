<?php

namespace Arete\CollectionPipeline;

class MockEntity {
    public $id;
    public $name;
    public $valueObject;

    public function __construct($id, $name, $valueObject = null) {
        $this->id = $id;
        $this->name = $name;
        $this->valueObject = $valueObject;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getValueObject() {
        return $this->valueObject;
    }
}
