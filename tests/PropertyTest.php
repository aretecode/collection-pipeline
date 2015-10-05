<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

use Arete\CollectionPipeline\MockEntity;

class PropertyTest extends CollectionPipelineTestSuite {
    public function testPropertyIsTheSameWhenManuallyDefined() {
        $array = $this->mockEntityArray;

        $resultManual = CP::from($array)->wheres('id', 'is_string', null, 'property')->all();
        $resultAuto = CP::from($array)->wheres('id', 'is_string')->all();

        $this->assertSame($resultAuto, $resultManual);
    }

    public function testProperty() {
        $array = $this->mockEntityArray;

        $result = CP::from($array)->wheres('id', 'is_string', null, 'property')->all();

        $expected = [$array[7]];
        $this->assertEquals($result[7], $expected[0]);
    }

    public function testPropertyNonExistent() {
        $array = $this->mockEntityArray;

        $result = CP::from($array)->wheres('nonExistentProperty', 'is_string')->all();

        $expected = [];
        $this->assertSame($result, $expected);
    }
}
