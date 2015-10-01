<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

class FunctionFromStringTest extends CollectionPipelineTestSuite {
    public function testNotIsString() {
        $array = $this->mockValueObjectArray;
        $result = CP::from($array)->wheres('getId', '!is_string')->all();

        $expected = [
            0 => $array[0],
            1 => $array[1],
            2 => $array[2],
            3 => $array[3],
            4 => $array[4],
            5 => $array[5],
            6 => $array[6],
            8 => $array[8],
            9 => $array[9],
            10 => $array[10],
        ];
        $this->assertEquals($result, $expected);
    }

    public function testIsString() {
        $array = $this->mockValueObjectArray;
        $result = CP::from($array)->wheres('getId', 'is_string')->all();

        $expected = [$array[7]];
        $this->assertEquals($result[7], $expected[0]);
    }

    public function testXY() {
        $array = $this->mockValueObjectArray;
        $arrayItWouldBeIn = ['joe', 'percy'];
        $result = CP::from($array)->wheresXY('getName', 'in_array', $arrayItWouldBeIn)->all();

        $expected = [$array[10]];
        $this->assertEquals($result[10], $expected[0]);
    }

    public function testYX() {
        $array = $this->mockValueObjectArray;
        $stringItWouldBeIn = 'joe,chrissy';
        $result = CP::from($array)->wheresYX('getName', 'containsSubString', $stringItWouldBeIn, 'callable')->all();

        $expected = [$array[10]];
        $this->assertEquals($result[10], $expected[0]);
    }
}
