<?php

namespace DevDeclan\Test\Unit\Redkina\StorageAdapter;

use PHPUnit\Framework\TestCase;
use DevDeclan\Redkina\Storage\Adapter\PhpRedis;
use Redis;

class PhpRedisTest extends TestCase
{
    public function testHappyPathLoad()
    {
        $phpRedis = $this->prophesize(Redis::class);

        $phpRedis->hGetAll('Foo.1')->willReturn(
            [
            'id' => '1'
            ]
        );

        $redis = new PhpRedis($phpRedis->reveal());

        $this->assertEquals(['id' => '1'], $redis->load('Foo.1'));
    }

    public function testHappyPathSave()
    {
        $phpRedis = $this->prophesize(Redis::class);

        $phpRedis->hMset('Foo.1', ['id' => '1'])->willReturn(true);

        $redis = new PhpRedis($phpRedis->reveal());

        $this->assertTrue($redis->save('Foo.1', ['id' => '1']));
    }

    public function testHappyPathBond()
    {
        $phpRedis = $this->prophesize(Redis::class);

        $phpRedis->zAdd(
            'relationships',
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

        $redis = new PhpRedis($phpRedis->reveal());

        $this->assertEquals(
            6,
            $redis->saveHexastore(
                [
                'spo:Foo.11:is_nemesis_of:Bar.22',
                'sop:Foo.11:Bar.22:is_nemesis_of',
                'ops:Bar.22:is_nemesis_of:Foo.11',
                'pso:is_nemesis_of:Foo.11:Bar.22',
                'pos:is_nemesis_of:Bar.22:Foo.11',
                'osp:Bar.22:Foo.11:is_nemesis_of'
                ]
            )
        );
    }

    public function testHappyPathLoadBonds()
    {
        $phpRedis = $this->prophesize(Redis::class);

        $phpRedis
            ->zRangeByLex('relationships', "[spo:Foo.11:is_nemesis_of:", "[spo:Foo.11:is_nemesis_of:\xff", null, null)
            ->willReturn(['spo:Foo.11:is_nemesis_of:Bar.22']);

        $redis = new PhpRedis($phpRedis->reveal());

        $this->assertEquals(['spo:Foo.11:is_nemesis_of:Bar.22'], $redis->queryHexastore('spo:Foo.11:is_nemesis_of:'));
    }

    public function testThatTransactionsCanBeUsed()
    {
        $phpRedis = $this->prophesize(Redis::class);

        $phpRedis->multi()->willReturn($phpRedis->reveal());
        $phpRedis->exec()->willReturn([]);
        $phpRedis->discard()->shouldBeCalledTimes(1);

        $redis = new PhpRedis($phpRedis->reveal());

        $redis->beginTransaction();
        $this->assertTrue($redis->isInTransaction());

        $redis->commit();
        $this->assertFalse($redis->isInTransaction());

        $redis->beginTransaction();
        $this->assertTrue($redis->isInTransaction());

        $redis->discard();
        $this->assertFalse($redis->isInTransaction());
    }
}
