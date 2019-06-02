<?php

namespace DevDeclan\Test\Unit\Redkina\Mapper;

use DevDeclan\Redkina\Mapper\Property\Generic;
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
    public function testIn()
    {
        $this->assertSame('hello', (new Generic())->in('hello'));
    }

    public function testOut()
    {
        $this->assertSame('hello', (new Generic())->out('hello'));
    }
}
