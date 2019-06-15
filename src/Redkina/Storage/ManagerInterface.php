<?php

namespace DevDeclan\Redkina\Storage;

interface ManagerInterface
{
    public function load(string $entityName, string $id): ?array;
    public function save(string $entityName, array $data): ?array;
    public function delete(string $entityName, string $id): bool;
    public function loadTriples(Triple $queryTriple): array;
    public function saveTriple(Triple $triple): object;
}
