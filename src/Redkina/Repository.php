<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Storage\ManagerInterface;

class Repository
{
    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @param  string $className
     * @param  string $id
     * @return bool|object
     */
    public function load(string $className, string $id): ? object
    {
        return $this->manager->load($className, $id);
    }

    /**
     * @param  object $entity
     * @return object|null
     * @throws \Exception
     */
    public function save(object $entity): ? object
    {
        return $this->manager->save($entity);
    }

    public function loadRelationships(object $entity): array
    {

    }

    public function saveRelationship(object $subjectEntity, string $predicate, object $objectEntity): object
    {
        return $this->manager->saveRelationship($subjectEntity, $predicate, $objectEntity);
    }
}
