<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

use Arete\CollectionPipeline\MockValueObject;

class MethodTest extends CollectionPipelineTestSuite {
    public function testMethodIsTheSameWhenManuallyDefined() {
        $array = $this->mockValueObjectArray;

        $resultManual = CP::from($array)->wheres('getId', 'is_string', null, 'method')->all();
        $resultAuto = CP::from($array)->wheres('getId', 'is_string')->all();

        $this->assertSame($resultAuto, $resultManual);
    }

    public function testMethod() {
        $array = $this->mockValueObjectArray;

        $result = CP::from($array)->wheres('getId', 'is_array')->all();

        $expected = [$array[10]];
        $this->assertEquals($result[10], $expected[0]);
    }

    public function testMethodNonExistent() {
        $array = $this->mockValueObjectArray;

        $result = CP::from($array)->wheres('nonExistentMethod', 'is_array')->all();
        $expected = [];
        $this->assertSame($result, $expected);
    }
}
