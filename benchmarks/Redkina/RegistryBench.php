<?php

namespace DevDeclan\Benchmark\Redkina;

use DevDeclan\Redkina\ClassLoader;
use DevDeclan\Redkina\MetadataExtractor;
use DevDeclan\Redkina\PropertyMetadataFactory;
use DevDeclan\Redkina\Registry;
use Doctrine\Common\Annotations\AnnotationReader;

class RegistryBench
{
    /**
     * @Revs(100)
     * @Iterations(5)
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function benchInitialise()
    {
        $classLoader = new ClassLoader(__DIR__ . '/../../test/support/Redkina/Entity');
        $extractor = new MetadataExtractor(new AnnotationReader(), new PropertyMetadataFactory());

        $registry = new Registry($classLoader, $extractor);

        $registry->initialise();
    }
}
