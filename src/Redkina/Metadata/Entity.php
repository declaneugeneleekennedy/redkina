<?php

namespace DevDeclan\Redkina\Metadata;

use DevDeclan\Redkina\MetadataInterface;

class Entity
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
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param  string            $name
     * @param  MetadataInterface $property
     * @return Entity
     */
    public function addProperty(string $name, MetadataInterface $property): self
    {
        $this->properties[$name] = $property;

        return $this;
    }
}
