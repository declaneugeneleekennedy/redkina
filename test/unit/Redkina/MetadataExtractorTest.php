<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;
use DevDeclan\Redkina\Metadata\Property\Generic;
use DevDeclan\Redkina\Metadata\Property\Integer;
use DevDeclan\Redkina\Metadata\Property\Timestamp;
use DevDeclan\Redkina\MetadataExtractor;
use DevDeclan\Redkina\PropertyMetadataFactory;
use DevDeclan\Test\Support\Redkina\Entity\Person;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MetadataExtractorTest extends TestCase
{
    /**
     * @var ReflectionClass
     */
    protected $reflected;

    /**
     * @var MetadataExtractor
     */
    protected $extractor;

    public function setUp(): void
    {
        parent::setUp();

        $this->reflected = new ReflectionClass(Person::class);
        $this->extractor = new MetadataExtractor(new AnnotationReader(), new PropertyMetadataFactory());
    }

    public function testHappyPath()
    {
        $metadata = $this->extractor->extract($this->reflected);

        $this->assertInstanceOf(EntityMetadata::class, $metadata);
        $this->assertEquals('Person', $metadata->getName());
        $this->assertEquals(Person::class, $metadata->getClassName());

        $this->assertInstanceOf(Generic::class, $metadata->getProperty('id'));
        $this->assertInstanceOf(Generic::class, $metadata->getProperty('firstName'));
        $this->assertInstanceOf(Generic::class, $metadata->getProperty('lastName'));

        $this->assertInstanceOf(Integer::class, $metadata->getProperty('age'));
        $this->assertInstanceOf(Timestamp::class, $metadata->getProperty('created'));
        $this->assertInstanceOf(Timestamp::class, $metadata->getProperty('updated'));
    }
}