<?php

namespace IdGenerator;

use DevDeclan\Redkina\IdGenerator\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    public function testHappyPath()
    {
        $generator = new Uuid();
        $this->assertRegExp(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-[4][0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $generator->generate()
        );
    }
}
