<?php

namespace DevDeclan\Test\Unit\Redkina\Storage\Generator\Key;

use DevDeclan\Redkina\Storage\Generator\Key\Standard;
use PHPUnit\Framework\TestCase;

class StandardTest extends TestCase
{
    public function testHappyPath()
    {
        $generator = new Standard();

        $this->assertEquals('Foo.qwerty', $generator->generate('Foo', 'qwerty'));
    }
}
