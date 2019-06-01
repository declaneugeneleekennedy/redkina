<?php

namespace Declaneugeneleekennedy\Redkina\Registry;

use Declaneugeneleekennedy\Redkina\Annotations\Entity;
use Declaneugeneleekennedy\Redkina\RegistryInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Go\ParserReflection\ReflectionFile;
use DirectoryIterator;
use ReflectionClass;

class Registry implements RegistryInterface
{
    /**
     * @var string
     */
    protected $entityPath;

    /**
     * @var Reader
     */
    protected $annotationReader;

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @param string $entityPath
     * @param Reader|null $annotationReader
     */
    public function __construct(string $entityPath, ? Reader $annotationReader = null)
    {
        $this->entityPath = $entityPath;
        $this->annotationReader = $annotationReader;
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function initialise(): void
    {
        if (is_null($this->annotationReader)) {
            $this->annotationReader = new AnnotationReader();
        }

        $entities = $this->getClassesInDirectory($this->entityPath);

        foreach ($entities as $className) {
            $this->processClassName($className);
        }
    }

    /**
     * @param string $name
     * @param string $className
     * @return Registry
     */
    public function addType(string $name, string $className): self
    {
        $this->types[$name] = $className;

        return $this;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getClassName(string $name): ? string
    {
        if (empty($this->types[$name])) {
            return null;
        }

        return $this->types[$name];
    }

    /**
     * @param string $className
     * @return string|null
     */
    public function getType(string $className): ? string
    {
        $flipped = array_flip($this->types);

        if (empty($flipped[$className])) {
            return null;
        }

        return $flipped[$className];
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getClassesInDirectory(string $path): array
    {
        $dir = new DirectoryIterator($path);

        $entities = [];

        foreach ($dir as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $results = $this->getClassesInDirectory($file->getRealPath());

                $entities = array_merge($entities, $results);
                continue;
            }

            $classes = $this->getClassesFromFile($file->getRealPath());

            $entities = array_merge($entities, $classes);
        }

        return $entities;
    }

    /**
     * @param string $file
     * @return array
     */
    protected function getClassesFromFile(string $file): array
    {
        $reflected = new ReflectionFile($file);

        $namespaces = $reflected->getFileNamespaces();

        $classes = [];
        foreach ($namespaces as $ns) {
            $nsClasses = $ns->getClasses();
            $classes = array_merge($classes, array_keys($nsClasses));
        }

        return $classes;
    }

    /**
     * @param string $className
     * @return $this|Registry
     * @throws \ReflectionException
     */
    protected function processClassName(string $className)
    {
        $reflectionClass = new ReflectionClass($className);

        if (!$this->isModel($reflectionClass)) {
            return $this;
        }

        $metadata = $this->getModelMetadata($reflectionClass);

        return $this->addType($metadata->getName(), $reflectionClass->getName());
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return Entity|null
     */
    protected function getModelMetadata(ReflectionClass $reflectionClass): ? Entity
    {
        $annotations = $this->annotationReader->getClassAnnotations($reflectionClass);
        foreach ($annotations as $anno) {
            if (is_a($anno, Entity::class)) {
                return $anno;
            }
        }

        return null;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return bool
     */
    protected function isModel(ReflectionClass $reflectionClass): bool
    {
        return !($this->getModelMetadata($reflectionClass) === null);
    }
}
