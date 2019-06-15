<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Metadata\Relationship;
use DevDeclan\Redkina\Storage\ManagerInterface;
use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\TripleBuilder;
use DevDeclan\Redkina\Storage\TripleEntity;
use DevDeclan\Redkina\Storage\SerializerInterface;
use DevDeclan\Redkina\Storage\UnserializerInterface;

class Repository
{
    /**
     * @var Registry
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

    public function __construct(Registry $registry, ManagerInterface $manager)
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
            $value = $this->unserialize($property->getUnserializer(), $data[$name]);

            $this->setObjectProperty($entity, $name, $value);
        }

        if ($preloadRelated) {
            foreach ($metadata->getRelationships() as $mapsTo => $relationshipMetadata) {
                $relatedEntities = $this->loadRelatedEntities(
                    $entity,
                    $relationshipMetadata->getPredicate(),
                    $relationshipMetadata->getRole(),
                    $relationshipMetadata->getEntityType()
                );

                $this->setObjectProperty($entity, $mapsTo, $relatedEntities);
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
                $data[$name] = $this->serialize($serializer, $this->getObjectProperty($entity, $name));
            }
        }

        $result = $this->manager->save($metadata->getName(), $data);

        if (!$result) {
            return null;
        }

        $entity = $this->setObjectProperty($entity, 'id', $result['id']);

        foreach ($metadata->getRelationships() as $mapsTo => $relationshipMetadata) {
            $value = $this->getObjectProperty($entity, $mapsTo);

            if (!$value) {
                continue;
            }

            foreach ($value as $related) {
                $this->saveRelatedEntity($entity, $relationshipMetadata, $related);
            }
        }

        return $entity;
    }

    public function delete(object $entity): bool
    {
        $entity = $this->load(get_class($entity), $entity->getId());
        $metadata = $this->registry->getEntityMetadata($this->registry->getEntityName(get_class($entity)));

        foreach ($metadata->getRelationships() as $mapsTo => $relationshipMetadata) {
            $relatedEntities = $this->getObjectProperty($entity, $mapsTo);

            if (!$relatedEntities) {
                continue;
            }

            /** @var RelatedEntity $relatedEntity */
            foreach ($relatedEntities as $relatedEntity) {
                $builder = $this->buildTriple();

                $triple = $builder
                    ->with($relationshipMetadata->getPredicate())
                    ->using($this->registry->getEntityName(get_class($entity)), $entity->getId())
                    ->asThe($relationshipMetadata->getRole())
                    ->forTripleIn($builder)
                    ->using($this->registry->getEntityName(get_class($relatedEntity->getEntity())), $relatedEntity->getEntity()->getId())
                    ->asThe($relationshipMetadata->getRole())
                    ->inverse()
                    ->forTripleIn($builder)
                    ->getTriple();

                $edge = $relatedEntity->getEdge();

                if ($edge) {
                    $triple->setEdge(new TripleEntity(
                        $this->registry->getEntityName(get_class($edge)),
                        $edge->getId()
                    ));
                }

                $this->manager->deleteTriple($triple);
            }
        }

        return $this->manager->delete($this->registry->getEntityName(get_class($entity)), $entity->getId());
    }

    public function loadRelatedEntities(
        object $entity,
        string $predicate,
        string $role,
        ?string $entityType = null
    ): array {
        $builder = $this->buildTriple();

        $builder
            ->with($predicate)
            ->using($this->registry->getEntityName(get_class($entity)), $entity->getId())
            ->asThe($role)
            ->forTripleIn($builder);

        if ($entityType) {
            $builder
                ->using($entityType)
                ->asThe($role)
                ->inverse()
                ->forTripleIn($builder);
        }

        /** @var Triple[] $relationships */
        $relationships = $this->manager->loadTriples($builder->getTriple());

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
        object $entity,
        Metadata\Relationship $relationshipMetadata,
        RelatedEntity $relatedEntity
    ): object {
        if ($relationshipMetadata->getRole() === Relationship::ROLE_SUBJECT) {
            $subjectEntity = $entity;
            $objectEntity = $relatedEntity->getEntity();
        } else {
            $subjectEntity = $relatedEntity->getEntity();
            $objectEntity = $entity;
        }

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

        return $this->manager->saveTriple($relationship);
    }

    protected function setObjectProperty(object $entity, string $name, $value): object
    {
        $method = 'set' . ucfirst($name);
        return $entity->$method($value);
    }

    protected function getObjectProperty(object $entity, string $name)
    {
        $method = 'get' . ucfirst($name);
        return $entity->$method($name);
    }

    protected function serialize(string $className, $value): string
    {
        if (!array_key_exists($className, $this->serializers)) {
            $this->serializers[$className] = new $className();
        }

        return $this->serializers[$className]->serialize($value);
    }

    protected function unserialize(string $className, string $value)
    {
        if (!array_key_exists($className, $this->unserializers)) {
            $this->unserializers[$className] = new $className();
        }

        return $this->unserializers[$className]->unserialize($value);
    }

    protected function buildTriple(): TripleBuilder
    {
        return new TripleBuilder();
    }
}
