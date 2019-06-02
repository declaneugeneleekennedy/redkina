<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\IdGeneratorInterface;
use DevDeclan\Redkina\Entity;
use DevDeclan\Redkina\Metadata\Property\Generic as GenericMetadata;
use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;
use DevDeclan\Redkina\RegistryInterface;
use DevDeclan\Redkina\Repository;
use DevDeclan\Redkina\StorageAdapterInterface;
use PHPUnit\Framework\TestCase;

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
    ];

    public function setUp(): void
    {
        parent::setUp();

        $entity = new class() extends Entity {
            protected $name;

            public function getName()
            {
                return $this->name;
            }

            public function setName(string $name)
            {
                $this->name = $name;

                return $this;
            }
        };

        $this->className = get_class($entity);

        $this->entityMetadata = (new EntityMetadata())
            ->setName('Foo')
            ->setClassName($this->className)
            ->addProperty('id', new GenericMetadata())
            ->addProperty('name', new GenericMetadata());
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

        $entity->setName($this->fooData['name']);

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
            ->setName($this->fooData['name']);

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
