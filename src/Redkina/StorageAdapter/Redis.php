<?php

namespace DevDeclan\Redkina\StorageAdapter;

use DevDeclan\Redkina\StorageAdapterInterface;
use Redis as Client;

/**
 * Default storage adapter which uses the PHP Redis client
 *
 * @package DevDeclan\Redkina\StorageAdapter
 */
class Redis implements StorageAdapterInterface
{
    /**
     * @var string
     */
    const BOND_INDEX = 'bonds';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $isInTransaction = false;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $key
     * @return array|null
     */
    public function load(string $key): ?array
    {
        return $this->client->hGetAll($key);
    }

    /**
     * @param string $key
     * @param array $data
     * @return bool
     */
    public function save(string $key, array $data): bool
    {
        return $this->client->hMSet($key, $data);
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function bond(array $keys): bool
    {
        return ($this->client->zAdd(
                self::BOND_INDEX,
                0, $keys[0],
                0, $keys[1],
                0, $keys[2],
                0, $keys[3],
                0, $keys[4],
                0, $keys[5],
                ) === 6);
    }

    public function loadBonds(string $hexKey): array
    {
        throw new \BadMethodCallException('Not yet implemented');
    }

    public function isIsInTransaction()
    {
        return $this->isInTransaction;
    }

    public function beginTransaction()
    {
        $this->isInTransaction = true;

        return $this->client->multi();
    }

    public function commit()
    {
        $result = $this->client->exec();
        $this->isInTransaction = false;

        return $result;
    }

    public function discard()
    {
        $this->client->discard();
        $this->isInTransaction = false;
    }
}
