<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Annotation;
use DevDeclan\Redkina\Metadata;
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
     * @var PropertyMetadataFactory
     */
    protected $propertyMetadataFactory;

    /**
     * @param AnnotationReader $annotationReader
     * @param PropertyMetadataFactory $propertyMetadataFactory
     */
    public function __construct(AnnotationReader $annotationReader, PropertyMetadataFactory $propertyMetadataFactory)
    {
        $this->annotationReader = $annotationReader;
        $this->propertyMetadataFactory = $propertyMetadataFactory;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return Metadata\Entity|null
     */
    public function extract(ReflectionClass $reflectionClass): ? Metadata\Entity
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

        $relationships = $this->extractRelationships($reflectionClass);

        foreach ($relationships as $mapsTo => $relationship) {
            $entityMetadata->addRelationship($mapsTo, $relationship);
        }

        return $entityMetadata;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return Annotation\Entity|null
     */
    protected function getEntityAnnotation(ReflectionClass $reflectionClass): ? Annotation\Entity
    {
        foreach ($this->annotationReader->getClassAnnotations($reflectionClass) as $annotation) {
            if (is_a($annotation, Annotation\Entity::class)) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return Metadata\Entity|null
     */
    protected function extractEntity(ReflectionClass $reflectionClass): ? Metadata\Entity
    {
        $entityAnnotation = $this->getEntityAnnotation($reflectionClass);

        if (is_null($entityAnnotation)) {
            return null;
        }

        return (new Metadata\Entity())
            ->setName($entityAnnotation->getName())
            ->setClassName($reflectionClass->getName());
    }

    protected function extractRelationships(ReflectionClass $reflectionClass): array
    {
        $relationships = [];

        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($this->annotationReader->getPropertyAnnotations($property) as $annotation) {
                /** @var $annotation Annotation\Relationship */
                if (is_a($annotation, Annotation\Relationship::class)) {
                    $relationships[$property->getName()] = new Metadata\Relationship(
                        $annotation->getPredicate(),
                        $annotation->getRole(),
                        $annotation->getEntityType()
                    );
                }
            }
        }

        return $relationships;
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
     * @return Metadata\PropertyInterface|null
     */
    protected function extractProperty(ReflectionProperty $property)
    {
        $propertyAnnotations = $this->annotationReader->getPropertyAnnotations($property);

        foreach ($propertyAnnotations as $annotation) {
            if (is_a($annotation, Annotation\PropertyInterface::class)) {
                return $this->propertyMetadataFactory->getByAnnotation($annotation);
            }
        }

        return null;
    }
}
