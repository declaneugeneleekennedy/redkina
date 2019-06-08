<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Relationship\Connectable;
use DevDeclan\Redkina\Relationship\Relationship;
use DevDeclan\Redkina\Storage\ManagerInterface;
use DevDeclan\Redkina\Storage\SerializerInterface;
use DevDeclan\Redkina\Storage\UnserializerInterface;

class Repository
{
    /**
     * @var RegistryInterface
     */
    protected $registry;

    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @var SerializerInterface[]
     */
    protected $serializers = [];

    /**
     * @var UnserializerInterface[]
     */
    protected $unserializers = [];

    public function __construct(RegistryInterface $registry, ManagerInterface $manager)
    {
        $this->registry = $registry;
        $this->manager = $manager;
    }

    /**
     * @param  string $className
     * @param  string $id
     * @return bool|object
     */
    public function load(string $className, string $id): ? object
    {
        $metadata = $this->registry->getEntityMetadata($this->registry->getEntityName($className));

        $data = $this->manager->load($metadata->getName(), $id);
        if (!$data) {
            return null;
        }

        $className = $metadata->getClassName();

        $entity = new $className();

        foreach ($metadata->getProperties() as $name => $property) {
            $unserializer = $property->getUnserializer();

            if (class_exists($unserializer)) {
                $this->setEntityProperty($entity, $name, $this->unserializeProperty($unserializer, $data[$name]));
            }
        }

        return $entity;
    }

    /**
     * @param  object $entity
     * @return object|null
     * @throws \Exception
     */
    public function save(object $entity): ? object
    {
        $metadata = $this->registry->getEntityMetadata($this->registry->getEntityName(get_class($entity)));

        $data = [];

        foreach ($metadata->getProperties() as $name => $property) {
            $serializer = $property->getSerializer();

            if (class_exists($serializer)) {
                $data[$name] = $this->serializeProperty($serializer, $this->getEntityProperty($entity, $name));
            }
        }

        $result = $this->manager->save($metadata->getName(), $data);

        if (!$result) {
            return null;
        }

        return $this->setEntityProperty($entity, 'id', $result['id']);
    }

    public function loadRelationships(object $entity, string $predicate = '*'): array
    {
        $subject = (new Connectable())
            ->setName($this->registry->getEntityName(get_class($entity)))
            ->setId($entity->getId());

        $relationship = (new Relationship())
            ->setSubject($subject)
            ->setPredicate($predicate);

        return $this->manager->loadRelationships($relationship);
    }

    public function saveRelationship(object $subjectEntity, string $predicate, object $objectEntity): object
    {
        $subject = (new Connectable())
            ->setName($this->registry->getEntityName(get_class($subjectEntity)))
            ->setId($subjectEntity->getId());

        $object = (new Connectable())
            ->setName($this->registry->getEntityName(get_class($objectEntity)))
            ->setId($objectEntity->getId());

        $relationship = (new Relationship())
            ->setSubject($subject)
            ->setPredicate($predicate)
            ->setObject($object);

        return $this->manager->saveRelationship($relationship);
    }

    protected function setEntityProperty(object $entity, string $name, $value): object
    {
        $method = 'set' . ucfirst($name);
        return $entity->$method($value);
    }

    protected function getEntityProperty(object $entity, string $name)
    {
        $method = 'get' . ucfirst($name);
        return $entity->$method($name);
    }

    protected function serializeProperty(string $className, $value): string
    {
        if (!array_key_exists($className, $this->serializers)) {
            $this->serializers[$className] = new $className();
        }

        return $this->serializers[$className]->serialize($value);
    }

    protected function unserializeProperty(string $className, $value): string
    {
        if (!array_key_exists($className, $this->unserializers)) {
            $this->unserializers[$className] = new $className();
        }

        return $this->unserializers[$className]->unserialize($value);
    }
}
