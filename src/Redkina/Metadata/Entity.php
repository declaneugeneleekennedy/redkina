<?php

namespace DevDeclan\Redkina\Metadata;

use DevDeclan\Redkina\MetadataInterface;
use Doctrine\Common\Annotations\Annotation;

/**
 * Model annotation handler
 *
 * @package DevDeclan\Redkina\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class Entity implements MetadataInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var PropertyInterface[]
     */
    protected $properties = [];

    /**
     * @var Relationship[]
     */
    protected $relationships = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return Entity
     */
    public function setName(string $name): Entity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param  string $className
     * @return Entity
     */
    public function setClassName(string $className): Entity
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return PropertyInterface[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param  string            $name
     * @param  PropertyInterface $property
     * @return Entity
     */
    public function addProperty(string $name, PropertyInterface $property): self
    {
        $this->properties[$name] = $property;

        return $this;
    }

    /**
     * @param string $name
     * @return PropertyInterface|null
     */
    public function getProperty(string $name): ? PropertyInterface
    {
        return $this->properties[$name] ?? null;
    }

    /**
     * @return Relationship[]
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }

    /**
     * @param string $mapsTo
     * @param Relationship $relationship
     * @return Entity
     */
    public function addRelationship(string $mapsTo, Relationship $relationship): Entity
    {
        $this->relationships[$mapsTo] = $relationship;

        return $this;
    }
}
