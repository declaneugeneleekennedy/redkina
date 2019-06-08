<?php

namespace DevDeclan\Redkina\Storage;

use DevDeclan\Redkina\Relationship\Relationship;

interface ManagerInterface
{
    public function load(string $entityName, string $id): ? array;
    public function save(string $entityName, array $data): ? array;
    public function loadRelationships(Relationship $relationship): array;
    public function saveRelationship(Relationship $relationship): object;
}
