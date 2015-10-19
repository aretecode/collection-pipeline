<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

class ArrayTest extends CollectionPipelineTestSuite {
    public function testIndexAccessor() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres(0, 'instanceof', MockEntity::CLASS, 'index')->all();
        $this->assertSame($result[0], $array[0]);
    }
    public function testKeyAccessor() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheresKey('==', 0)->all();
        $this->assertSame($result[0], $array[0]);
    }
}
