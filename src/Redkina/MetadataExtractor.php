<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Annotation\Entity;
use DevDeclan\Redkina\Annotation\Property\Integer;
use DevDeclan\Redkina\Annotation\PropertyInterface;
use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;
use DevDeclan\Redkina\Metadata\Property\Integer as IntegerMetadata;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionProperty;

class MetadataExtractor
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

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

    protected function getEntityAnnotation(ReflectionClass $reflectionClass): ? Entity
    {
        foreach ($this->annotationReader->getClassAnnotations($reflectionClass) as $annotation) {
            if (is_a($annotation, Entity::class)) {
                return $annotation;
            }
        }

        return null;
    }

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

    protected function extractProperties(ReflectionClass $reflectionClass): array
    {
        $properties = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $properties[$property->getName()] = $this->extractProperty($property);
        }

        return $properties;
    }

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

    protected function propertyFactory(PropertyInterface $propertyAnnotation)
    {
        switch (get_class($propertyAnnotation)) {
            case Integer::class:
            default:
                return new IntegerMetadata();
        }
    }
}
