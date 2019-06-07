<?php

namespace DevDeclan\Redkina\Storage\Manager;

use DevDeclan\Redkina\Generator\IdInterface;
use DevDeclan\Redkina\Generator\KeyInterface;
use DevDeclan\Redkina\Mapper\Entity as EntityMapper;
use DevDeclan\Redkina\RegistryInterface;
use DevDeclan\Redkina\Relationship\Connectable;
use DevDeclan\Redkina\Relationship\Hexastore;
use DevDeclan\Redkina\Relationship\HexKey;
use DevDeclan\Redkina\Relationship\Relationship;
use DevDeclan\Redkina\Storage\AdapterInterface;
use DevDeclan\Redkina\Storage\ManagerInterface;

class Standard implements ManagerInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var RegistryInterface
     */
    protected $registry;

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
        RegistryInterface $registry,
        IdInterface $idGenerator,
        KeyInterface $keyGenerator
    ) {
        $this->adapter = $adapter;
        $this->registry = $registry;
        $this->idGenerator = $idGenerator;
        $this->keyGenerator = $keyGenerator;
    }

    public function load(string $className, string $id): ? object
    {
        $key = $this->keyGenerator->generate(
            $this->registry->getEntityName($className),
            $id
        );

        $data = $this->adapter->load($key);

        if (!$data) {
            return null;
        }

        $metadata = $this->registry->getClassMetadata($className);
        $mapper = new EntityMapper($metadata);

        return $mapper->out($data);
    }

    public function save(object $entity): object
    {
        if (!$entity->getId()) {
            return $this->insert($entity);
        }

        return $this->update($entity);
    }

    public function loadRelationships(object $subjectEntity, string $predicate = '*', string $className = null): array
    {
        $relationship = new Relationship();

        $subject = (new Connectable())
            ->setId($subjectEntity->getId())
            ->setName($this->registry->getEntityName($subjectEntity));

        $relationship
            ->setSubject($subject)
            ->setPredicate($predicate);

        if ($className) {
            $object = (new Connectable())
                ->setName($this->registry->getEntityName($className));

            $relationship->setObject($object);
        }

        $query = (new Hexastore($relationship))->getQuery();

        $keys = $this->adapter->queryHexastore($query);

        $relationships = [];

        foreach ($keys as $key) {
            $relationships[] = HexKey::hydrate($key);
        }

        return $relationships;
    }

    public function saveRelationship(object $subjectEntity, string $predicate, object $objectEntity): object
    {
        $subject = (new Connectable())
            ->setId($subjectEntity->getId())
            ->setName($this->registry->getEntityName(get_class($subjectEntity)));

        $object = (new Connectable())
            ->setId($objectEntity->getId())
            ->setName($this->registry->getEntityName(get_class($objectEntity)));

        $relationship = (new Relationship())
            ->setSubject($subject)
            ->setPredicate($predicate)
            ->setObject($object);

        $this->adapter->beginTransaction();

        $keys = (new Hexastore($relationship))->getKeys();

        $this->adapter->saveHexastore($keys);

        if ($relationship->hasEdge()) {
            $edge = $relationship->getEdge();

            $edgeKey = $this->keyGenerator->generate(
                $this->registry->getEntityName($edge),
                $edge->getId()
            );

            foreach ($keys as $key) {
                $this->adapter->saveEdge($key, $edgeKey);
            }
        }

        $this->adapter->commit();

        return $relationship;
    }

    /**
     * @param  object $entity
     * @return object|null
     * @throws \Exception
     */
    protected function insert(object $entity): ? object
    {
        $entity->setId($this->idGenerator->generate());

        return $this->update($entity);
    }

    /**
     * @param  object $entity
     * @return object|null
     */
    protected function update(object $entity): ? object
    {
        $entityMetadata = $this->registry->getClassMetadata(get_class($entity));

        $mapper = new EntityMapper($entityMetadata);

        $data = $mapper->in($entity);

        $key = $this->keyGenerator->generate(
            $this->registry->getEntityName(get_class($entity)),
            $entity->getId()
        );

        return $this->adapter->save($key, $data) ? $entity : null;
    }
}
