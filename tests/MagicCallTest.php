<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;
use Arete\CollectionPipeline\MockEntity;

class MagicCallTest extends CollectionPipelineTestSuite {

    public function testMagicallyMapping() {
        $array = $this->mockEntityArray;
        
        $result = cp($array)->getValueObject('map')->getValue('===', true)->all();
        $expected = [$array[7]->getValueObject()];

        $result = sort($result);
        $expected = sort($expected);

        $this->assertSame($expected, $result);
    }

    public function testGettingValueObjectsMagically() {
        $array = $this->mockEntityArray;

        $result = cp($array)->getValueObject()->all();
        $expected = [
            $array[0], 
            $array[2], 
            $array[6], 
            $array[7], 
            $array[8], 
            $array[9], 
            $array[10], 
        ];

        $result = sort($result);
        $expected = sort($expected);
        $this->assertSame($expected, $result);
    }
}
