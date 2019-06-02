<?php

namespace DevDeclan\Test\Unit\Redkina\Relationship;

use DevDeclan\Redkina\Relationship\Hexastore;
use DevDeclan\Test\Unit\Redkina\HexTestCase;

class HexastoreTest extends HexTestCase
{
    public function testHappyPath()
    {
        $hexastore = new Hexastore($this->happyPathRelationship);

        $sort = function (array $in) {
            sort($in);

            return $in;
        };

        $generatedKeys = $sort($hexastore->getKeys());
        $expectedKeys = $sort($this->keys);

        $this->assertEquals($expectedKeys, $generatedKeys);
    }
}
