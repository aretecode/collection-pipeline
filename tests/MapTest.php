<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\CollectionPipeline as CP;
use Arete\CollectionPipeline\MockEntity;

class MapTest extends CollectionPipelineTestSuite {
    public function testMapWheres() {
        $array = $this->mockEntityArray;
       
        $result = 
        cp($array)
        ->mapWheres('getValueObject')
        ->wheres('getValue', '===', true)
        ->all();

        $expected = [$array[7]->getValueObject()];

        $result = sort($result);
        $expected = sort($expected);

        $this->assertSame($expected, $result);
    }
}
