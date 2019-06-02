<?php

namespace DevDeclan\Test\Unit\Redkina\Mapper;

use DevDeclan\Redkina\Mapper\Property\Timestamp;
use PHPUnit\Framework\TestCase;

class TimestampTest extends TestCase
{
    const TIME_STRING = '2019-06-02T01:29:11+00:00';

    public function testIn()
    {
        $dt = new \DateTime(self::TIME_STRING);
        $this->assertEquals(self::TIME_STRING, (new Timestamp())->in($dt));
    }

    public function testOut()
    {
        $this->assertEquals(date_create(self::TIME_STRING), (new Timestamp())->out(self::TIME_STRING));
    }
}
