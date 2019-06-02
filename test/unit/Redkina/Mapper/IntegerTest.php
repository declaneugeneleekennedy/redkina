<?php

namespace DevDeclan\Test\Unit\Redkina\Mapper;

use DevDeclan\Redkina\Mapper\Property\Integer;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
    public function testIn()
    {
        $this->assertSame('123', (new Integer())->in(123));
    }

    public function testOut()
    {
        $this->assertSame(123, (new Integer())->out('123'));
    }
}
