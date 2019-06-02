<?php

namespace DevDeclan\Redkina;

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
     * @return bool|Entity
     */
    public function load(string $className, string $id): ? Entity
    {
        $key = $this->generateKeyByTypeAndId($this->registry->getEntityName($className), $id);

        $data = $this->storage->load($key);

        if (!$data) {
            return null;
        }

        $metadata = $this->registry->getClassMetadata($className);
        $mapper = new \DevDeclan\Redkina\Mapper\Entity($metadata);

        return $mapper->out($data);
    }

    /**
     * @param  Entity $entity
     * @return Entity|null
     * @throws \Exception
     */
    public function save(Entity $entity): ? Entity
    {
        if (!$entity->getId()) {
            return $this->insert($entity);
        }

        return $this->update($entity);
    }

    /**
     * @param  Entity $entity
     * @return Entity|null
     * @throws \Exception
     */
    protected function insert(Entity $entity): ? Entity
    {
        $entity->setId($this->idGenerator->generate());

        return $this->update($entity);
    }

    /**
     * @param  Entity $entity
     * @return Entity|null
     */
    protected function update(Entity $entity): ? Entity
    {
        $entityMetadata = $this->registry->getClassMetadata(get_class($entity));

        $mapper = new \DevDeclan\Redkina\Mapper\Entity($entityMetadata);

        $data = $mapper->in($entity);

        return $this->storage->save($this->generateKey($entity), $data) ? $entity : null;
    }

    /**
     * @param  Entity $entity
     * @return string
     */
    protected function generateKey(Entity $entity): string
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

    protected function getFromEntity(Entity $entity, string $property)
    {
        $method = $this->generatePropertyMethodName('get', $property);

        return $entity->$method();
    }

    protected function setToEntity(Entity $entity, string $property, $value)
    {
        $method = $this->generatePropertyMethodName('set', $property);

        return $entity->$method($value);
    }

    protected function generatePropertyMethodName(string $prefix, string $name)
    {
        return $prefix . ucfirst($name);
    }
}
