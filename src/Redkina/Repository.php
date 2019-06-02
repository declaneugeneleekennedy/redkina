<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Mapper\Entity as EntityMapper;

class Repository
{
    /**
     * @var RegistryInterface
     */
    protected $registry;

    /**
     * @var StorageAdapterInterface
     */
    protected $storage;

    /**
     * @var IdGeneratorInterface
     */
    protected $idGenerator;

    /**
     * @param RegistryInterface       $registry
     * @param StorageAdapterInterface $storage
     * @param IdGeneratorInterface    $idGenerator
     */
    public function __construct(
        RegistryInterface $registry,
        StorageAdapterInterface $storage,
        IdGeneratorInterface $idGenerator
    ) {
        $this->registry = $registry;
        $this->storage = $storage;
        $this->idGenerator = $idGenerator;
    }

    /**
     * @param  string $className
     * @param  string $id
     * @return bool|object
     */
    public function load(string $className, string $id): ? object
    {
        $key = $this->generateKeyByTypeAndId($this->registry->getEntityName($className), $id);

        $data = $this->storage->load($key);

        if (!$data) {
            return null;
        }

        $metadata = $this->registry->getClassMetadata($className);
        $mapper = new EntityMapper($metadata);

        return $mapper->out($data);
    }

    /**
     * @param  object $entity
     * @return object|null
     * @throws \Exception
     */
    public function save(object $entity): ? object
    {
        if (!$entity->getId()) {
            return $this->insert($entity);
        }

        return $this->update($entity);
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

        return $this->storage->save($this->generateKey($entity), $data) ? $entity : null;
    }

    /**
     * @param  object $entity
     * @return string
     */
    protected function generateKey(object $entity): string
    {
        $type = $this->registry->getEntityName(get_class($entity));
        return $this->generateKeyByTypeAndId($type, $entity->getId());
    }

    /**
     * @param  string $typeName
     * @param  string $id
     * @return string
     */
    protected function generateKeyByTypeAndId(string $typeName, string $id)
    {
        return sprintf('%s.%s', $typeName, $id);
    }
}
