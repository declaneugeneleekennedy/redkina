<?php

namespace Declaneugeneleekennedy\Redkina;

/**
 * Storage is provided as an adapter so that any Redis library can be dropped used with a
 * little bit of work
 *
 * @package Declaneugeneleekennedy\Redkina
 */
interface StorageAdapterInterface
{
    /**
     * Load a hashed object
     *
     * @param string $key
     * @return array|null
     */
    public function load(string $key): ? array;

    /**
     * Save a hashed object
     *
     * @param string $key
     * @param array $data
     * @return bool
     */
    public function save(string $key, array $data): bool;

    /**
     * Add a hexastore which defines a bond to a ZSET
     *
     * @param array $keys
     * @return bool
     */
    public function bond(array $keys): bool;

    public function loadBonds(string $hexKey): array;
}
