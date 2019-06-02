<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Annotation\Entity;
use DevDeclan\Redkina\Annotation\Property\Generic;
use DevDeclan\Redkina\Annotation\Property\Integer;
use DevDeclan\Redkina\Annotation\Property\Timestamp;
use DevDeclan\Redkina\Annotation\PropertyInterface;
use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;
use DevDeclan\Redkina\Metadata\Property\Generic as GenericMetadata;
use DevDeclan\Redkina\Metadata\Property\Integer as IntegerMetadata;
use DevDeclan\Redkina\Metadata\Property\Timestamp as TimestampMetadata;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionProperty;

class MetadataExtractor
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return EntityMetadata|null
     */
    public function extract(ReflectionClass $reflectionClass): ? EntityMetadata
    {
        $entityMetadata = $this->extractEntity($reflectionClass);

        if (is_null($entityMetadata)) {
            return null;
        }

        $properties = $this->extractProperties($reflectionClass);

        foreach ($properties as $property => $metadata) {
            if (is_null($metadata)) {
                continue;
            }

            $entityMetadata->addProperty($property, $metadata);
        }

        return $entityMetadata;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return Entity|null
     */
    protected function getEntityAnnotation(ReflectionClass $reflectionClass): ? Entity
    {
        foreach ($this->annotationReader->getClassAnnotations($reflectionClass) as $annotation) {
            if (is_a($annotation, Entity::class)) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return EntityMetadata|null
     */
    protected function extractEntity(ReflectionClass $reflectionClass): ? EntityMetadata
    {
        $entityAnnotation = $this->getEntityAnnotation($reflectionClass);

        if (is_null($entityAnnotation)) {
            return null;
        }

        return (new EntityMetadata())
            ->setName($entityAnnotation->getName())
            ->setClassName($reflectionClass->getName());
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     */
    protected function extractProperties(ReflectionClass $reflectionClass): array
    {
        $properties = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $properties[$property->getName()] = $this->extractProperty($property);
        }

        return $properties;
    }

    /**
     * @param ReflectionProperty $property
     * @return \DevDeclan\Redkina\Metadata\PropertyInterface|null
     */
    protected function extractProperty(ReflectionProperty $property)
    {
        $propertyAnnotations = $this->annotationReader->getPropertyAnnotations($property);

        foreach ($propertyAnnotations as $annotation) {
            if (is_a($annotation, PropertyInterface::class)) {
                return $this->propertyFactory($annotation);
            }
        }

        return null;
    }

    /**
     * @param PropertyInterface $propertyAnnotation
     * @return \DevDeclan\Redkina\Metadata\PropertyInterface
     */
    protected function propertyFactory(PropertyInterface $propertyAnnotation)
    {
        switch (get_class($propertyAnnotation)) {
            case Integer::class:
                return new IntegerMetadata();
            case Timestamp::class:
                return new TimestampMetadata();
            case Generic::class:
            default:
                return new GenericMetadata();
        }
    }
}
