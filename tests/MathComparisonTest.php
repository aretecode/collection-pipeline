<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

class MathComparisonTest extends CollectionPipelineTestSuite {
    public function testReverseGreaterThan() {
        $array = $this->mockEntityArray;

        // would be ->getId() < 100, but with 'yx', it is 100 < ->getId()
        $result = CP::from($array)->wheres('getId', 'is_int')->wheres('getId', '<', 100, 'method', 'yx')->all();
        $expected = [2 => $array[2]];
        $this->assertEquals($result, $expected);
    }

    public function testGreaterThanOrEqualTo() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres('getId', 'is_int')->wheres('getId', '>=', 111)->all();

        $expected = [2 => $array[2]];
        $this->assertEquals($result, $expected);
    }

    public function testLessThanOrEqualToWithoutStrings() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres('getId', '!is_string')->wheres('getId', '!is_null')->wheres('getId', '<=', 6)->all();

        $expected = [
            6 => $array[6],
            8 => $array[8],
        ];
        $this->assertEquals($result, $expected);
    }

    public function testLessThanOrEqualTo() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres('getId', '<=', 6)->all();

        $expected = [
            0 => $array[0],
            6 => $array[6],
            7 => $array[7],
            8 => $array[8],
        ];
        $this->assertEquals($result, $expected);
    }

    public function testLessThan() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres('getId', 'is_int')->wheres('getId', '<', 0)->all();

        $expected = [6 => $array[6]];
        $this->assertEquals($result, $expected);
    }

    public function testEqualTo() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres('getId', '===', 6)->all();

        $expected = [8 => $array[8]];
        $this->assertEquals($result, $expected);
    }

    public function testGreaterThan() {
        $array = $this->mockEntityArray;
        $result = CP::from($array)->wheres('getId', 'is_int')->wheres('getId', '>', 110)->all();

        $expected = [2 => $array[2]];
        $this->assertEquals($result, $expected);
    }
}
