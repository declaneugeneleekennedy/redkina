<?php

namespace DevDeclan\Redkina;

use DirectoryIterator;
use Go\ParserReflection\ReflectionFile;

class ClassLoader
{
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param  string $path
     * @return string[]
     */
    public function getClasses(string $path = null): array
    {
        if (is_null($path)) {
            $path = $this->path;
        }

        $dir = new DirectoryIterator($path);

        $entities = [];

        foreach ($dir as $file) {
            if ($file->isDot()) {
                continue;
            }

            if ($file->isDir()) {
                $results = $this->getClasses($file->getRealPath());

                $entities = array_merge($entities, $results);
                continue;
            }

            $classes = $this->getClassesFromFile($file->getRealPath());

            $entities = array_merge($entities, $classes);
        }

        return $entities;
    }

    /**
     * @param  string $file
     * @return string[]
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
}
