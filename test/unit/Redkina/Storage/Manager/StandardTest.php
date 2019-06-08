<?php


namespace DevDeclan\Test\Unit\Redkina\Storage\Manager;

use DateTime;
use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;
use DevDeclan\Redkina\Metadata\Property\Generic as GenericMetadata;
use DevDeclan\Redkina\Metadata\Property\Integer as IntegerMetadata;
use DevDeclan\Redkina\Metadata\Property\Timestamp as TimestampMetadata;
use DevDeclan\Redkina\RegistryInterface;
use DevDeclan\Redkina\Storage\AdapterInterface;
use DevDeclan\Redkina\Storage\Generator\IdInterface;
use DevDeclan\Redkina\Storage\Generator\Key\Standard as KeyGenerator;
use DevDeclan\Redkina\Storage\Manager\Standard;
use PHPUnit\Framework\TestCase;

class StandardTest extends TestCase
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var EntityMetadata
     */
    protected $entityMetadata;

    /**
     * @var array
     */
    protected $fooData = [
        'id' => 'this-is-not-a-real-uuid-oh',
        'name' => 'this is also not a real name',
        'num' => '123',
        'time' => '2019-06-02T01:29:11+00:00',
    ];

    public function testHappyPathLoad()
    {
        $storage = $this->prophesize(AdapterInterface::class);

        $storage->load('Foo.this-is-not-a-real-uuid-oh')->willReturn($this->fooData);

        $generator = $this->prophesize(IdInterface::class);

        $repo = new Standard(
            $storage->reveal(),
            $generator->reveal(),
            new KeyGenerator()
        );

        $this->assertEquals($this->fooData, $repo->load('Foo', 'this-is-not-a-real-uuid-oh'));
    }

    public function testLoadKeyNotFound()
    {
        $storage = $this->prophesize(AdapterInterface::class);

        $storage->load('Foo.this-is-not-a-real-uuid-oh')->willReturn(null);

        $generator = $this->prophesize(IdInterface::class);

        $repo = new Standard(
            $storage->reveal(),
            $generator->reveal(),
            new KeyGenerator()
        );

        $this->assertNull($repo->load('Foo', 'this-is-not-a-real-uuid-oh'));
    }

    public function testHappyPathSaveCreate()
    {
        $storage = $this->prophesize(AdapterInterface::class);

        $storage->save('Foo.this-is-not-a-real-uuid-oh', $this->fooData)->willReturn(true);

        $generator = $this->prophesize(IdInterface::class);

        $generator->generate()->willReturn('this-is-not-a-real-uuid-oh');

        $repo = new Standard(
            $storage->reveal(),
            $generator->reveal(),
            new KeyGenerator()
        );

        $data = $this->fooData;
        unset($data['id']);

        $this->assertEquals($this->fooData, $repo->save('Foo', $this->fooData));
    }

    public function testHappyPathSaveUpdate()
    {
        $storage = $this->prophesize(AdapterInterface::class);

        $storage->save('Foo.this-is-not-a-real-uuid-oh', $this->fooData)->willReturn(true);

        $generator = $this->prophesize(IdInterface::class);

        $repo = new Standard(
            $storage->reveal(),
            $generator->reveal(),
            new KeyGenerator()
        );

        $this->assertEquals($this->fooData, $repo->save('Foo', $this->fooData));
    }
}
