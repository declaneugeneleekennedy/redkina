<?php

namespace DevDeclan\Redkina\Storage\Adapter;

use DevDeclan\Redkina\Storage\AdapterInterface;
use Redis;

/**
 * Default storage adapter which uses the PHP Redis client
 *
 * @package DevDeclan\Redkina\StorageAdapter
 */
class PhpRedis implements AdapterInterface
{
    /**
     * @var string
     */
    const TRIPLES_INDEX = 'triples';

    /**
     * @var Redis
     */
    protected $client;

    /**
     * @var bool
     */
    protected $inTransaction = false;

    /**
     * @param Redis $client
     */
    public function __construct(Redis $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string $key
     * @return array|null
     */
    public function load(string $key): ?array
    {
        return $this->client->hGetAll($key);
    }

    /**
     * @param  string $key
     * @param  array  $data
     * @return bool
     */
    public function save(string $key, array $data): bool
    {
        return $this->client->hMSet($key, $data);
    }

    public function delete(string $key): bool
    {
        return ($this->client->del($key) === 1);
    }

    /**
     * @param  array $keys
     * @return bool
     */
    public function saveTripleSet(array $keys): bool
    {
        return ($this->client->zAdd(
            self::TRIPLES_INDEX,
            0,
            $keys[0],
            0,
            $keys[1],
            0,
            $keys[2],
            0,
            $keys[3],
            0,
            $keys[4],
            0,
            $keys[5],
        ) === 6);
    }

    public function queryTripleStore(string $query, ?int $start = null, ?int $size = null): array
    {
        return $this->client->zRangeByLex(
            self::TRIPLES_INDEX,
            "[{$query}",
            "[{$query}\xff",
            $start,
            $size
        );
    }

    public function deleteTriple(string $triple): bool
    {
        return $this->client->zDelete(self::TRIPLES_INDEX, $triple);
    }

    public function saveEdge(string $key, string $edgeKey): bool
    {
        return $this->client->set($key, $edgeKey);
    }

    public function loadEdge(string $key): ?string
    {
        return $this->client->get($key);
    }

    public function isInTransaction(): bool
    {
        return $this->inTransaction;
    }

    public function beginTransaction()
    {
        $this->inTransaction = true;

        $this->client->multi();
    }

    public function commit()
    {
        $result = $this->client->exec();
        $this->inTransaction = false;

        return $result;
    }

    public function discard()
    {
        $this->client->discard();
        $this->inTransaction = false;
    }
}
