<?php

namespace Arete\CollectionPipeline;

use Arete\CollectionPipeline\MockValueObject;

class CollectionPipelineTestSuite extends \PHPUnit_Framework_TestCase {
    protected $mockValueObjectArray;

    protected function setUp() {
        $this->mockValueObjectArray = array(
            new MockValueObject(null, "other"), #0
            new MockValueObject(10, "other"),   #1
            new MockValueObject(111, "other"),  #2
            new MockValueObject(11, "other"),   #3
            new MockValueObject(12, "other"),   #4
            new MockValueObject(13, "other"),   #5
            new MockValueObject(-2, "other"),   #6
            new MockValueObject("eh", "frank"), #7
            new MockValueObject(6, "frank"),    #8
            new MockValueObject(10, "frank"),   #9
            new MockValueObject(["eh"], "joe"), #10
        );
    }
}
