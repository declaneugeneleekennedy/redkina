<?php

namespace DevDeclan\Redkina\Storage\Manager;

use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\AdapterInterface;
use DevDeclan\Redkina\Storage\Generator\IdInterface;
use DevDeclan\Redkina\Storage\Generator\KeyInterface;
use DevDeclan\Redkina\Storage\TripleBuilder;
use DevDeclan\Redkina\Storage\TripleEntity;
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

    public function load(string $entityName, string $id): ?array
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

    public function save(string $entityName, array $data): ?array
    {
        if (empty($data['id'])) {
            return $this->insert($entityName, $data);
        }

        return $this->update($entityName, $data);
    }

    public function delete(string $entityName, string $id): bool
    {
        $this->deleteTriples($entityName, $id);

        return $this->adapter->delete($this->keyGenerator->generate($entityName, $id));
    }

    public function loadTriples(Triple $queryTriple, int $offset = 0, int $size = 10): array
    {
        $query = (new TripleSet($queryTriple))->getQuery();

        $keys = $this->adapter->queryTripleStore($query, $offset, $size);

        $triples = [];

        foreach ($keys as $key) {
            $triple = TripleKey::hydrate($key);

            $edgeKey = $this->adapter->loadEdge($key);

            if ($edgeKey) {
                list($entityName, $id) = explode(TripleKey::ENTITY_KEY_DELIMITER, $edgeKey);
                $triple->setEdge(new TripleEntity($entityName, $id));
            }

            $triples[] = $triple;
        }

        return $triples;
    }

    public function saveTriple(Triple $triple): object
    {
        $keys = (new TripleSet($triple))->getKeys();

        $this->adapter->saveTripleSet($keys);

        if ($triple->hasEdge()) {
            $edge = $triple->getEdge();

            $edgeKey = $this->keyGenerator->generate(
                $edge->getName(),
                $edge->getId()
            );

            foreach ($keys as $key) {
                $this->adapter->saveEdge($key, $edgeKey);
            }
        }

        return $triple;
    }

    protected function insert(string $entityName, array $data): ?array
    {
        $data['id'] = $this->idGenerator->generate();

        return $this->update($entityName, $data);
    }

    protected function update(string $entityName, array $data): ?array
    {
        $key = $this->keyGenerator->generate(
            $entityName,
            $data['id']
        );

        return $this->adapter->save($key, $data) ? $data : null;
    }

    protected function deleteTriples(string $entityName, string $id)
    {
        $builder = new TripleBuilder();

        foreach (['subject', 'object'] as $role) {
            $queryTriple = $builder
                ->with('*')
                ->using($entityName, $id)
                ->asThe($role)
                ->forTripleIn($builder)
                ->getTriple();

            $query = (new TripleSet($queryTriple))->getQuery();
            $keys = $this->adapter->queryTripleStore($query);

            dump($query);
            dump($keys);

            foreach ($keys as $key) {
                $this->adapter->deleteTriple($key);

                $edgeKey = $this->adapter->loadEdge($key);

                if ($edgeKey) {
                    $this->adapter->delete($edgeKey);
                }

                $this->adapter->delete($key);
            }
        }
    }
}
