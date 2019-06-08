<?php

namespace DevDeclan\Redkina\Storage\Manager;

use DevDeclan\Redkina\Relationship\Hexastore;
use DevDeclan\Redkina\Relationship\HexKey;
use DevDeclan\Redkina\Relationship\Relationship;
use DevDeclan\Redkina\Storage\AdapterInterface;
use DevDeclan\Redkina\Storage\Generator\IdInterface;
use DevDeclan\Redkina\Storage\Generator\KeyInterface;
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

    public function loadRelationships(Relationship $relationship): array
    {
        $query = (new Hexastore($relationship))->getQuery();

        $keys = $this->adapter->queryHexastore($query);

        $relationships = [];

        foreach ($keys as $key) {
            $relationships[] = HexKey::hydrate($key);
        }

        return $relationships;
    }

    public function saveRelationship(Relationship $relationship): object
    {
        $this->adapter->beginTransaction();

        $keys = (new Hexastore($relationship))->getKeys();

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
