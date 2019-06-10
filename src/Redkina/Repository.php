<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Storage\ManagerInterface;
use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\TripleEntity;
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
            $value = $this->unserializeProperty($property->getUnserializer(), $data[$name]);

            $this->setEntityProperty($entity, $name, $value);
        }

        foreach ($metadata->getRelationships() as $mapsTo => $relationshipMetadata) {
            $relatedEntities = $this->loadRelatedEntities(
                $entity,
                $relationshipMetadata->getRole(),
                $relationshipMetadata->getPredicate()
            );

            $this->setEntityProperty($entity, $mapsTo, $relatedEntities);
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

        $entity = $this->setEntityProperty($entity, 'id', $result['id']);

        foreach ($metadata->getRelationships() as $mapsTo => $relationshipMetadata) {
            $value = $this->getEntityProperty($entity, $mapsTo);

            if (!$value) {
                continue;
            }

            foreach ($value as $related) {
                $this->saveRelatedEntity($entity, $relationshipMetadata->getPredicate(), $related);
            }
        }

        return $entity;
    }

    public function delete(object $entity): bool
    {
        return $this->manager->delete($this->registry->getEntityName(get_class($entity)), $entity->getId());
    }

    public function loadRelatedEntities(object $entity, string $role, string $predicate): array
    {
        $target = new TripleEntity($this->registry->getEntityName(get_class($entity)), $entity->getId());

        $targetMethod = 'set' . ucfirst($role);

        $query = (new Triple())
            ->$targetMethod($target)
            ->setPredicate($predicate);

        /** @var Triple[] $relationships */
        $relationships = $this->manager->loadRelationships($query);

        $results = [];

        foreach ($relationships as $relationship) {
            $object = $relationship->getObject();

            $related = $this->load(
                $this->registry->getClassName($object->getName()),
                $object->getId()
            );

            if (!$related) {
                continue;
            }

            $relatedEntity = new RelatedEntity($predicate, $related);

            $edge = $relationship->getEdge();

            if ($edge) {
                $relatedEntity->setEdge($edge);
            }

            $results[] = $relatedEntity;
        }

        return $results;
    }

    public function saveRelatedEntity(
        object $subjectEntity,
        string $predicate,
        object $objectEntity,
        ? object $edgeEntity = null
    ): object {
        $subject = new TripleEntity(
            $this->registry->getEntityName(get_class($subjectEntity)),
            $subjectEntity->getId()
        );

        $object = new TripleEntity(
            $this->registry->getEntityName(get_class($objectEntity)),
            $objectEntity->getId()
        );

        $relationship = (new Triple())
            ->setSubject($subject)
            ->setObject($object)
            ->setPredicate($predicate);

        if ($edgeEntity) {
            $edge = new TripleEntity(
                $this->registry->getEntityName($edgeEntity),
                $edgeEntity->getId()
            );

            $relationship->setEdge($edge);
        }

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
