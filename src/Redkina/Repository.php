<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Metadata\Relationship;
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
     * @param string $className
     * @param string $id
     * @param bool $preloadRelated
     * @return bool|object
     */
    public function load(string $className, string $id, bool $preloadRelated = true): ?object
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

        if ($preloadRelated) {
            foreach ($metadata->getRelationships() as $mapsTo => $relationshipMetadata) {
                $relatedEntities = $this->loadRelatedEntities(
                    $entity,
                    $relationshipMetadata->getRole(),
                    $relationshipMetadata->getPredicate()
                );

                $this->setEntityProperty($entity, $mapsTo, $relatedEntities);
            }
        }

        return $entity;
    }

    /**
     * @param  object $entity
     * @return object|null
     * @throws \Exception
     */
    public function save(object $entity): ?object
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
                $this->saveRelatedEntity($entity, $related);
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
        $queryTripleEntity = new TripleEntity($this->registry->getEntityName(get_class($entity)), $entity->getId());

        $setMethod = 'set' . ucfirst($role);

        $query = (new Triple())
            ->$setMethod($queryTripleEntity)
            ->setPredicate($predicate);

        /** @var Triple[] $relationships */
        $relationships = $this->manager->loadRelationships($query);

        $results = [];

        foreach ($relationships as $relationship) {
            $resultTripleEntity =  $role === Relationship::ROLE_SUBJECT ?
                $relationship->getObject() : $relationship->getSubject();

            $related = $this->load(
                $this->registry->getClassName($resultTripleEntity->getName()),
                $resultTripleEntity->getId(),
                false
            );

            if (!$related) {
                continue;
            }

            $relatedEntity = new RelatedEntity($predicate, $related);

            $edge = $relationship->getEdge();

            if ($edge) {
                $edgeEntity = $this->load(
                    $this->registry->getClassName($edge->getName()),
                    $edge->getId(),
                    false
                );

                $relatedEntity->setEdge($edgeEntity);
            }

            $results[] = $relatedEntity;
        }

        return $results;
    }

    public function saveRelatedEntity(
        object $subjectEntity,
        RelatedEntity $relatedEntity
    ): object {
        $objectEntity = $relatedEntity->getEntity();

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
            ->setPredicate($relatedEntity->getPredicate());

        $edgeEntity = $relatedEntity->getEdge();

        if ($edgeEntity) {
            $edge = new TripleEntity(
                $this->registry->getEntityName(get_class($edgeEntity)),
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

    protected function unserializeProperty(string $className, string $value)
    {
        if (!array_key_exists($className, $this->unserializers)) {
            $this->unserializers[$className] = new $className();
        }

        return $this->unserializers[$className]->unserialize($value);
    }
}
