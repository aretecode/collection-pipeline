<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

class WheresEachTest extends CollectionPipelineTestSuite {
    public function testInstanceOf() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheresEach('instanceof', MockEntity::CLASS)->all();

        $this->assertEquals($result, $array);
    }

    public function testNotInstanceOf() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheresEach('!instanceof', MockEntity::CLASS)->all();

        $this->assertEquals($result, []);
    }
}
