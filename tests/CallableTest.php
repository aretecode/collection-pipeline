<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;

class CallableTest extends CollectionPipelineTestSuite {

    protected $keyValueTest = "";

    public function testCallable() {
        $array = $this->mockValueObjectArray;
        $result = CP::from($array)->wheres('getId', function($value) {
            // 6 is true
            if ($value == 2 || $value == 6) {
                return true;
            }
            return false;
        })->all();

        $expected = [
            8 => $array[8], 
        ];  
        $this->assertEquals($result, $expected);
    }

    public function testCallableManual() {
        $array = $this->mockValueObjectArray;
        $resultManual = CP::from($array)->wheres('getId', function($value) {
            // 6 is true
            if ($value == 2 || $value == 6) {
                return true;
            }
            return false;
        }, null, 'callable')->all();
        $resultAuto = CP::from($array)->wheres('getId', function($value) {
            // 6 is true
            if ($value == 2 || $value == 6) {
                return true;
            }
            return false;
        })->all();

        $this->assertSame($resultManual, $resultAuto);
    }

    public function testCallableXY() {
        $somethingElse = 'not needed';
        $array = $this->mockValueObjectArray;
        $result = CP::from($array)->wheresXY('getId', function($value, $key) {
            
            $this->keyValueTest = $key;

            // 6 is true
            if ($value == 2 || $value == 6) {
                return true;
            }
            return false;
        }, $somethingElse)->all();

        $expected = [
            8 => $array[8], 
        ];          

        $this->assertEquals($result, $expected);
        $this->assertSame($this->keyValueTest, $somethingElse);
    }

    public function testCallableYX() {
        $somethingElse = 'not needed';
        $array = $this->mockValueObjectArray;
        $result = CP::from($array)->wheresXY('getId', function($key, $value) {
          
            $this->keyValueTest = $value;

            // 6 is true
            if ($key == 2 || $key == 6) {
                return true;
            }
            return false;
        }, $somethingElse)->all();

        $expected = [
            8 => $array[8], 
        ];  

        $this->assertEquals($result, $expected);
        $this->assertSame($this->keyValueTest, $somethingElse);
    }
}
