<?php

namespace DevDeclan\Redkina\Storage\Manager;

use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\AdapterInterface;
use DevDeclan\Redkina\Storage\Generator\IdInterface;
use DevDeclan\Redkina\Storage\Generator\KeyInterface;
use DevDeclan\Redkina\Storage\TripleSet;
use DevDeclan\Redkina\Storage\TripleKey;
use DevDeclan\Redkina\Storage\ManagerInterface;

class Standard implements ManagerInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var IdInterface
     */
    protected $idGenerator;

    /**
     * @var KeyInterface
     */
    protected $keyGenerator;

    public function __construct(
        AdapterInterface $adapter,
        IdInterface $idGenerator,
        KeyInterface $keyGenerator
    ) {
        $this->adapter = $adapter;
        $this->idGenerator = $idGenerator;
        $this->keyGenerator = $keyGenerator;
    }

    public function load(string $entityName, string $id): ? array
    {
        $key = $this->keyGenerator->generate(
            $entityName,
            $id
        );

        $data = $this->adapter->load($key);

        if (!$data) {
            return null;
        }

        return $data;
    }

    public function save(string $entityName, array $data): ? array
    {
        if (empty($data['id'])) {
            return $this->insert($entityName, $data);
        }

        return $this->update($entityName, $data);
    }

    public function delete(string $entityName, string $id): bool
    {
        return $this->adapter->delete($this->keyGenerator->generate($entityName, $id));
    }

    public function loadRelationships(Triple $relationship, int $offset = 0, int $size = 10): array
    {
        $query = (new TripleSet($relationship))->getQuery();

        $keys = $this->adapter->queryHexastore($query, $offset, $size);

        $relationships = [];

        foreach ($keys as $key) {
            $relationships[] = TripleKey::hydrate($key);
        }

        return $relationships;
    }

    public function saveRelationship(Triple $relationship): object
    {
        $this->adapter->beginTransaction();

        $keys = (new TripleSet($relationship))->getKeys();

        $this->adapter->saveHexastore($keys);

        if ($relationship->hasEdge()) {
            $edge = $relationship->getEdge();

            $edgeKey = $this->keyGenerator->generate(
                $edge->getName(),
                $edge->getId()
            );

            foreach ($keys as $key) {
                $this->adapter->saveEdge($key, $edgeKey);
            }
        }

        $this->adapter->commit();

        return $relationship;
    }

    protected function insert(string $entityName, array $data): ? array
    {
        $data['id'] = $this->idGenerator->generate();

        return $this->update($entityName, $data);
    }

    protected function update(string $entityName, array $data): ? array
    {
        $key = $this->keyGenerator->generate(
            $entityName,
            $data['id']
        );

        return $this->adapter->save($key, $data) ? $data : null;
    }
}
