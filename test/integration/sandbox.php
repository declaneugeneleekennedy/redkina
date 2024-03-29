<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use DevDeclan\Redkina\ClassLoader;
use DevDeclan\Redkina\MetadataExtractor;
use DevDeclan\Redkina\PropertyMetadataFactory;
use DevDeclan\Redkina\Registry;
use DevDeclan\Redkina\Repository;
use DevDeclan\Redkina\Storage\Adapter\PhpRedis;
use DevDeclan\Redkina\Storage\Generator;
use DevDeclan\Redkina\Storage\Manager\Standard;
use DevDeclan\Test\Support\Redkina\Entity\Actor;
use DevDeclan\Test\Support\Redkina\Entity\Movie;
use DevDeclan\Test\Support\Redkina\Factory\RedkinaStore;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use League\FactoryMuffin\FactoryMuffin;

AnnotationRegistry::registerLoader('class_exists');

$registry = new Registry(
    new ClassLoader(__DIR__ . '/../support/Redkina/Entity'),
    new MetadataExtractor(new AnnotationReader(), new PropertyMetadataFactory())
);

$redis = new Redis();

$redis->connect('redis');

$manager = new Standard(
    new PhpRedis($redis),
    new Generator\Id\Uuid(),
    new Generator\Key\Standard()
);

$registry->initialise();

$repository = new Repository($registry, $manager);

$fm = new FactoryMuffin((new RedkinaStore())->setRepository($repository));

$fm->loadFactories(__DIR__ . '/../support/Redkina/Factory/Definition');

$movie = $repository->load(Movie::class, '4e479dfe-2aff-4eb3-9a78-752ce9c956b9');
