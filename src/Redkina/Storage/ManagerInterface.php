<?php

namespace DevDeclan\Redkina\Storage;

interface ManagerInterface
{
    public function load(string $className, string $id): ? object;
    public function save(object $entity): ? object;
    public function loadRelationships(object $subjectEntity, string $predicate, string $className = null): array;
    public function saveRelationship(object $subjectEntity, string $predicate, object $objectEntity): object;
}
