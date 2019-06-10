<?php

namespace DevDeclan\Test\Unit\Redkina\Storage;

use DevDeclan\Redkina\Storage\TripleSet;
use DevDeclan\Test\Unit\Redkina\TripleTestCase;

class TripleSetTest extends TripleTestCase
{
    public function testHappyPath()
    {
        $hexastore = new TripleSet($this->happyPathRelationship);

        $sort = function (array $in) {
            sort($in);

            return $in;
        };

        $generatedKeys = $sort($hexastore->getKeys());
        $expectedKeys = $sort($this->keys);

        $this->assertEquals($expectedKeys, $generatedKeys);
    }
}
