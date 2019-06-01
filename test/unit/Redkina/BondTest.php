<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\Bond;
use DevDeclan\Redkina\BondableInterface;
use DevDeclan\Redkina\RegistryInterface;
use PHPUnit\Framework\TestCase;

class BondTest extends TestCase
{
    public function testHappyPath()
    {

        $subject = $this->prophesize(BondableInterface::class);
        $subject->getId()->willReturn(11);

        $subject = $subject->reveal();

        $object = $this->prophesize(BondableInterface::class);
        $object->getId()->willReturn(22);

        $object = $object->reveal();

        $registry = $this->prophesize(RegistryInterface::class);

        $registry->getEntityName(get_class($subject))->willReturn('Foo');
        $registry->getEntityName(get_class($object))->willReturn('Bar');

        $bond = new Bond($registry->reveal());

        $keys = $bond->create($subject, $object, 'is_friend_of');

        $this->assertCount(6, $keys);

        $this->assertContains('spo:Foo.11:is_friend_of:Bar.22', $keys);
        $this->assertContains('pos:is_friend_of:Bar.22:Foo.11', $keys);
        $this->assertContains('osp:Bar.22:Foo.11:is_friend_of', $keys);
        $this->assertContains('ops:Bar.22:is_friend_of:Foo.11', $keys);
        $this->assertContains('pso:is_friend_of:Foo.11:Bar.22', $keys);
        $this->assertContains('sop:Foo.11:Bar.22:is_friend_of', $keys);
    }
}
