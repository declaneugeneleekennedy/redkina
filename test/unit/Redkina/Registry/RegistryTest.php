<?php

namespace DevDeclan\Test\Unit\Redkina\Registry;

use DevDeclan\Redkina\Registry\Registry;
use DevDeclan\Test\Support\Redkina\Entity\Fake;
use DevDeclan\Test\Support\Redkina\Entity\Person;
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

        $this->registry = new Registry(__DIR__ . '/../../../support/Redkina/Entity');
        $this->registry->initialise();
    }

    public function testThatEntitiesCanBeIdentifiedByClassName()
    {
        $this->assertEquals('Person', $this->registry->getType(Person::class));
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
        $this->assertNull($this->registry->getType(Fake::class));
    }
}