<?php

namespace DevDeclan\Redkina\Metadata\Property;

use DevDeclan\Redkina\MapperInterface;
use DevDeclan\Redkina\Metadata\PropertyInterface;
use DevDeclan\Redkina\MetadataInterface;
use DevDeclan\Redkina\Mapper\Property\Generic as Mapper;

/**
 * @package DevDeclan\Redkina\Metadata\Property
 */
class Relationship implements MetadataInterface, PropertyInterface
{
    /**
     * @var array
     */
    protected $entityTypes = [];

    /**
     * @var string
     */
    protected $predicate;

    /**
     * @param array $entityTypes
     * @param string $predicate
     */
    public function __construct(array $entityTypes, ? string $predicate = null)
    {
        $this->entityTypes = $entityTypes;
        $this->predicate = $predicate;
    }

    /**
     * @return array
     */
    public function getEntityTypes(): array
    {
        return $this->entityTypes;
    }

    /**
     * @param array $entityTypes
     * @return Relationship
     */
    public function setEntityTypes(array $entityTypes): Relationship
    {
        $this->entityTypes = $entityTypes;
        return $this;
    }

    /**
     * @return string
     */
    public function getPredicate(): string
    {
        return $this->predicate;
    }

    /**
     * @param string $predicate
     * @return Relationship
     */
    public function setPredicate(string $predicate): Relationship
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * @return MapperInterface
     */
    public function getMapper(): MapperInterface
    {
        return new Mapper();
    }
}
