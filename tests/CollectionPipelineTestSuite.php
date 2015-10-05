<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\MockEntity;

class CollectionPipelineTestSuite extends \PHPUnit_Framework_TestCase {
    protected $mockEntityArray;

    protected function setUp() {
        $this->mockEntityArray = array(
            new MockEntity(null, "other"), #0
            new MockEntity(10, "other"),   #1
            new MockEntity(111, "other"),  #2
            new MockEntity(11, "other"),   #3
            new MockEntity(12, "other"),   #4
            new MockEntity(13, "other"),   #5
            new MockEntity(-2, "other"),   #6
            new MockEntity("eh", "frank"), #7
            new MockEntity(6, "frank"),    #8
            new MockEntity(10, "frank"),   #9
            new MockEntity(["eh"], "joe"), #10
        );
    }
}
