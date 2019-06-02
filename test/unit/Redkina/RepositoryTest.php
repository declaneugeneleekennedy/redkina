<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\IdGeneratorInterface;
use DevDeclan\Redkina\Metadata\Property\Generic as GenericMetadata;
use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;
use DevDeclan\Redkina\Metadata\Property\Integer as IntegerMetadata;
use DevDeclan\Redkina\Metadata\Property\Timestamp as TimestampMetadata;
use DevDeclan\Redkina\RegistryInterface;
use DevDeclan\Redkina\Repository;
use DevDeclan\Redkina\StorageAdapterInterface;
use PHPUnit\Framework\TestCase;
use DateTime;

class RepositoryTest extends TestCase
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

    public function setUp(): void
    {
        parent::setUp();

        $entity = new class() {
            protected $id;
            protected $name;
            protected $num;
            protected $time;

            public function getId()
            {
                return $this->id;
            }

            public function setId(string $id)
            {
                $this->id = $id;

                return $this;
            }

            public function getName()
            {
                return $this->name;
            }

            public function setName(string $name)
            {
                $this->name = $name;

                return $this;
            }

            public function getNum()
            {
                return $this->num;
            }


            public function setNum($num)
            {
                $this->num = $num;
                return $this;
            }

            public function getTime()
            {
                return $this->time;
            }

            public function setTime($time)
            {
                $this->time = $time;
                return $this;
            }
        };

        $this->className = get_class($entity);

        $this->entityMetadata = (new EntityMetadata())
            ->setName('Foo')
            ->setClassName($this->className)
            ->addProperty('id', new GenericMetadata())
            ->addProperty('name', new GenericMetadata())
            ->addProperty('num', new IntegerMetadata())
            ->addProperty('time', new TimestampMetadata());
    }

    public function testHappyPathLoad()
    {
        $registry = $this->prophesize(RegistryInterface::class);

        $registry->getEntityName($this->className)->willReturn('Foo');
        $registry->getClassName('Foo')->willReturn($this->className);
        $registry->getClassMetadata($this->className)->willReturn($this->entityMetadata);

        $storage = $this->prophesize(StorageAdapterInterface::class);

        $storage->load('Foo.this-is-not-a-real-uuid-oh')->willReturn($this->fooData);

        $generator = $this->prophesize(IdGeneratorInterface::class);

        $repo = new Repository($registry->reveal(), $storage->reveal(), $generator->reveal());

        $this->assertInstanceOf($this->className, $repo->load($this->className, 'this-is-not-a-real-uuid-oh'));
    }

    public function testLoadKeyNotFound()
    {
        $registry = $this->prophesize(RegistryInterface::class);

        $registry->getEntityName($this->className)->willReturn('Foo');
        $registry->getClassName('Foo')->willReturn($this->className);
        $registry->getClassMetadata($this->className)->willReturn($this->entityMetadata);

        $storage = $this->prophesize(StorageAdapterInterface::class);

        $storage->load('Foo.this-is-not-a-real-uuid-oh')->willReturn(null);

        $generator = $this->prophesize(IdGeneratorInterface::class);

        $repo = new Repository($registry->reveal(), $storage->reveal(), $generator->reveal());

        $this->assertNull($repo->load($this->className, 'this-is-not-a-real-uuid-oh'));
    }

    public function testHappyPathSaveCreate()
    {
        $cn = $this->className;
        $entity = new $cn('Foo');

        $entity
            ->setName($this->fooData['name'])
            ->setNum((int)$this->fooData['num'])
            ->setTime(new DateTime($this->fooData['time']));

        $registry = $this->prophesize(RegistryInterface::class);

        $registry->getEntityName($this->className)->willReturn('Foo');
        $registry->getClassName('Foo')->willReturn($this->className);
        $registry->getClassMetadata($this->className)->willReturn($this->entityMetadata);

        $storage = $this->prophesize(StorageAdapterInterface::class);

        $storage->save('Foo.this-is-not-a-real-uuid-oh', $this->fooData)->willReturn(true);

        $generator = $this->prophesize(IdGeneratorInterface::class);

        $generator->generate()->willReturn('this-is-not-a-real-uuid-oh');

        $repo = new Repository($registry->reveal(), $storage->reveal(), $generator->reveal());

        $this->assertInstanceOf($this->className, $repo->save($entity));
    }

    public function testHappyPathSaveUpdate()
    {
        $cn = $this->className;
        $entity = new $cn('Foo');

        $entity
            ->setId($this->fooData['id'])
            ->setName($this->fooData['name'])
            ->setNum((int)$this->fooData['num'])
            ->setTime(new DateTime($this->fooData['time']));

        $registry = $this->prophesize(RegistryInterface::class);

        $registry->getEntityName($this->className)->willReturn('Foo');
        $registry->getClassName('Foo')->willReturn($this->className);
        $registry->getClassMetadata($this->className)->willReturn($this->entityMetadata);

        $storage = $this->prophesize(StorageAdapterInterface::class);

        $storage->save('Foo.this-is-not-a-real-uuid-oh', $this->fooData)->willReturn(true);

        $generator = $this->prophesize(IdGeneratorInterface::class);

        $repo = new Repository($registry->reveal(), $storage->reveal(), $generator->reveal());

        $this->assertInstanceOf($this->className, $repo->save($entity));
    }
}
