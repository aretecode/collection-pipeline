<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\MockEntity;
use Arete\CollectionPipeline\MockValueObject;

class CollectionPipelineTestSuite extends \PHPUnit_Framework_TestCase {
    protected $mockEntityArray;

    protected function setUp() {
        $this->mockEntityArray = array(
            new MockEntity(null, "other", new MockValueObject(100)), #0
            new MockEntity(10, "other", new MockValueObject(50.55)),   #1
            new MockEntity(111, "other", new MockValueObject(false)),  #2
            new MockEntity(11, "other"),                               #3
            new MockEntity(12, "other"),                               #4
            new MockEntity(13, "other"),                               #5
            new MockEntity(-2, "other", new MockValueObject(null)),    #6
            new MockEntity("eh", "frank", new MockValueObject(true)),  #7
            new MockEntity(6, "frank", new MockValueObject([])),       #8
            new MockEntity(10, "frank", new MockValueObject(new MockValueObject([]))),        #9
            new MockEntity(["eh"], "joe", new MockValueObject("100")), #10
        );
    }
}
