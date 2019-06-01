<?php

namespace DevDeclan\Test\Unit\Redkina\Registry;

use DevDeclan\Redkina\ClassLoader;
use DevDeclan\Redkina\MetadataExtractor;
use DevDeclan\Redkina\Registry\Registry;
use DevDeclan\Test\Support\Redkina\Entity\Fake;
use DevDeclan\Test\Support\Redkina\Entity\Person;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    /**
     * @var Registry
     */
    protected $registry;

    public function setUp(): void
    {
        parent::setUp();

        $classLoader = new ClassLoader(__DIR__ . '/../../../support/Redkina/Entity');
        $metadataExtractor = new MetadataExtractor(new AnnotationReader());

        $this->registry = new Registry($classLoader, $metadataExtractor);
        $this->registry->initialise();
    }

    public function testThatEntitiesCanBeIdentifiedByClassName()
    {
        $this->assertEquals('Person', $this->registry->getEntityName(Person::class));
    }

    public function testThatEntitiesCanBeIdentifiedByType()
    {
        $this->assertEquals(Person::class, $this->registry->getClassName('Person'));
    }

    public function testThatNonEntityTypesWillReturnNull()
    {
        $this->assertNull($this->registry->getClassName('Fake'));
    }

    public function testThatNonEntityClassNamesWillReturnNull()
    {
        $this->assertNull($this->registry->getEntityName(Fake::class));
    }
}
