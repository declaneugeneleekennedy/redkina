<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Metadata\Entity;
use ReflectionClass;
use ReflectionException;

class Registry
{
    /**
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * @var MetadataExtractor
     */
    protected $metadataExtractor;

    /**
     * @var array
     */
    protected $entities = [];

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @param ClassLoader       $classLoader
     * @param MetadataExtractor $metadataExtractor
     */
    public function __construct(ClassLoader $classLoader, MetadataExtractor $metadataExtractor)
    {
        $this->classLoader = $classLoader;
        $this->metadataExtractor = $metadataExtractor;
    }

    /**
     * @throws ReflectionException
     */
    public function initialise(): void
    {
        $classes = $this->classLoader->getClasses();

        foreach ($classes as $class) {
            $reflected = new ReflectionClass($class);
            $metadata = $this->metadataExtractor->extract($reflected);

            if (is_null($metadata)) {
                continue;
            }

            $this->registerEntity($metadata->getName(), $metadata);
            $this->registerClass($reflected->getName(), $metadata);
        }
    }

    /**
     * @param  string         $entityName
     * @param  Entity $metadata
     * @return Registry
     */
    public function registerEntity(string $entityName, Entity $metadata): self
    {
        $this->entities[$entityName] = $metadata;

        return $this;
    }

    /**
     * @param  string         $className
     * @param  Entity $metadata
     * @return Registry
     */
    public function registerClass(string $className, Entity $metadata): self
    {
        $this->classes[$className] = $metadata;

        return $this;
    }

    /**
     * @param string $className
     * @return Entity|null
     */
    public function getClassMetadata(string $className): ?Entity
    {
        return $this->classes[$className] ?? null;
    }

    /**
     * @param string $entityName
     * @return Entity|null
     */
    public function getEntityMetadata(string $entityName): ?Entity
    {
        return $this->entities[$entityName] ?? null;
    }

    /**
     * @param  string $entityName
     * @return string|null
     */
    public function getClassName(string $entityName): ?string
    {
        $metadata = $this->getEntityMetadata($entityName);

        return $metadata ? $metadata->getClassName() : null;
    }

    /**
     * @param  string $className
     * @return string|null
     */
    public function getEntityName(string $className): ?string
    {
        $metadata = $this->getClassMetadata($className);

        return $metadata ? $metadata->getName() : null;
    }
}
