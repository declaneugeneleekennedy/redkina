<?php

namespace DevDeclan\Redkina\Mapper;

use DevDeclan\Redkina\MapperInterface;
use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;

class Entity implements MapperInterface
{
    /**
     * @var EntityMetadata
     */
    protected $entityMetadata;

    public function __construct(EntityMetadata $entityMetadata)
    {
        $this->entityMetadata = $entityMetadata;
    }

    public function in($input)
    {
        $data = [];

        foreach ($this->entityMetadata->getProperties() as $name => $propertyMetadata) {
            $rawValue = $this->getFromEntity($input, $name);
            $data[$name] = $propertyMetadata->getMapper()->in($rawValue);
        }

        return $data;
    }

    public function out($output)
    {
        $className = $this->entityMetadata->getClassName();

        $entity = new $className();

        foreach ($output as $name => $rawValue) {
            $propertyMetadata = $this->entityMetadata->getProperty($name);
            $this->setToEntity($entity, $name, $propertyMetadata->getMapper()->out($rawValue));
        }

        return $entity;
    }

    protected function getFromEntity($entity, string $property)
    {
        $method = $this->generatePropertyMethodName('get', $property);

        return $entity->$method();
    }

    protected function setToEntity($entity, string $property, $value)
    {
        $method = $this->generatePropertyMethodName('set', $property);

        return $entity->$method($value);
    }

    protected function generatePropertyMethodName(string $prefix, string $name)
    {
        return $prefix . ucfirst($name);
    }
}
