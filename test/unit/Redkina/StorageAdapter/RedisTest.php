<?php

namespace DevDeclan\Test\Unit\Redkina\StorageAdapter;

use PHPUnit\Framework\TestCase;
use Redis as PhpRedis;
use DevDeclan\Redkina\StorageAdapter\Redis;

class RedisTest extends TestCase
{
    public function testHappyPathLoad()
    {
        $phpRedis = $this->prophesize(PhpRedis::class);

        $phpRedis->hGetAll('Foo.1')->willReturn([
            'id' => '1'
        ]);

        $redis = new Redis($phpRedis->reveal());

        $this->assertEquals(['id' => '1'], $redis->load('Foo.1'));
    }

    public function testHappyPathSave()
    {
        $phpRedis = $this->prophesize(PhpRedis::class);

        $phpRedis->hMset('Foo.1', ['id' => '1'])->willReturn(true);

        $redis = new Redis($phpRedis->reveal());

        $this->assertTrue($redis->save('Foo.1', ['id' => '1']));
    }

    public function testHappyPathBond()
    {
        $phpRedis = $this->prophesize(PhpRedis::class);

        $phpRedis->zAdd(
            'bonds',
            0,
            'spo:Foo.11:is_nemesis_of:Bar.22',
            0,
            'sop:Foo.11:Bar.22:is_nemesis_of',
            0,
            'ops:Bar.22:is_nemesis_of:Foo.11',
            0,
            'pso:is_nemesis_of:Foo.11:Bar.22',
            0,
            'pos:is_nemesis_of:Bar.22:Foo.11',
            0,
            'osp:Bar.22:Foo.11:is_nemesis_of'
        )->willReturn(6);

        $redis = new Redis($phpRedis->reveal());

        $this->assertEquals(6, $redis->bond([
            'spo:Foo.11:is_nemesis_of:Bar.22',
            'sop:Foo.11:Bar.22:is_nemesis_of',
            'ops:Bar.22:is_nemesis_of:Foo.11',
            'pso:is_nemesis_of:Foo.11:Bar.22',
            'pos:is_nemesis_of:Bar.22:Foo.11',
            'osp:Bar.22:Foo.11:is_nemesis_of'
        ]));
    }

    public function testHappyPathLoadBonds()
    {
        $phpRedis = $this->prophesize(PhpRedis::class);
        $redis = new Redis($phpRedis->reveal());

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Not yet implemented');

        $redis->loadBonds('fooooooooo');
    }

    public function testThatTransactionsCanBeUsed()
    {
        $phpRedis = $this->prophesize(PhpRedis::class);

        $phpRedis->multi()->willReturn($phpRedis->reveal());
        $phpRedis->exec()->willReturn([]);
        $phpRedis->discard()->shouldBeCalledTimes(1);

        $redis = new Redis($phpRedis->reveal());

        $redis->beginTransaction();
        $this->assertTrue($redis->isIsInTransaction());

        $redis->commit();
        $this->assertFalse($redis->isIsInTransaction());

        $redis->beginTransaction();
        $this->assertTrue($redis->isIsInTransaction());

        $redis->discard();
        $this->assertFalse($redis->isIsInTransaction());
    }
}