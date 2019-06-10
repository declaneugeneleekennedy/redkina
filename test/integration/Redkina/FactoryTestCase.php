<?php

namespace DevDeclan\Test\Integration\Redkina;

use DevDeclan\Redkina\ClassLoader;
use DevDeclan\Redkina\MetadataExtractor;
use DevDeclan\Redkina\PropertyMetadataFactory;
use DevDeclan\Redkina\Registry\Registry;
use DevDeclan\Redkina\Repository;
use DevDeclan\Redkina\Storage\Adapter\PhpRedis;
use DevDeclan\Redkina\Storage\Generator;
use DevDeclan\Redkina\Storage\Manager\Standard;
use DevDeclan\Test\Support\Redkina\Factory\RedkinaStore;
use Doctrine\Common\Annotations\AnnotationReader;
use League\FactoryMuffin\FactoryMuffin;
use PHPUnit\Framework\TestCase;
use Redis;

class FactoryTestCase extends TestCase
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Standard
     */
    protected $manager;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var FactoryMuffin
     */
    protected $fm;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new Registry(
            new ClassLoader(__DIR__ . '/../../support/Redkina/Entity'),
            new MetadataExtractor(new AnnotationReader(), new PropertyMetadataFactory())
        );

        $redis = new Redis();

        $redis->connect('redis');

        $this->manager = new Standard(
            new PhpRedis($redis),
            new Generator\Id\Uuid(),
            new Generator\Key\Standard()
        );

        $this->registry->initialise();

        $this->repository = new Repository($this->registry, $this->manager);

        $this->fm = new FactoryMuffin((new RedkinaStore())->setRepository($this->repository));
    }
}
