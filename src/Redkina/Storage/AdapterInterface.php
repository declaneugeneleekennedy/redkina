<?php

namespace DevDeclan\Redkina\Storage;

/**
 * Storage is provided as an adapter so that any Redis library can be dropped in and used with a little bit of work.
 *
 * @package DevDeclan\Redkina
 */
interface AdapterInterface
{
    /**
     * Load a hashed object
     *
     * @param  string $key
     * @return array|null
     */
    public function load(string $key): ? array;

    /**
     * Save a hashed object
     *
     * @param  string $key
     * @param  array  $data
     * @return bool
     */
    public function save(string $key, array $data): bool;

    public function delete(string $key): bool;

    /**
     * Add a hexastore which defines a relationship to a ZSET
     *
     * @param  array $keys
     * @return bool
     */
    public function saveHexastore(array $keys): bool;

    public function queryHexastore(string $hexKey, int $offset = 0, int $size = 10): array;

    public function saveEdge(string $key, string $edgeKey): bool;

    public function isInTransaction(): bool;

    public function beginTransaction();

    public function commit();

    public function discard();
}
