<?php

namespace DevDeclan\Redkina\Metadata;

use DevDeclan\Redkina\MetadataInterface;

class Entity implements MetadataInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var array
     */
    protected $properties = [];

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

    public function getRelationshipProperties(): array
    {
        return array_filter($this->getProperties(), function ($property) {
            return (is_a($property, Relationship::class));
        });
    }
}
