<?php

namespace DevDeclan\Redkina;

class RelatedEntity
{
    /**
     * @var string
     */
    protected $predicate;

    /**
     * @var object
     */
    protected $entity;

    /**
     * @var object|null
     */
    protected $edge;

    /**
     * @param string $predicate
     * @param object $edge
     * @param object $entity
     */
    public function __construct(string $predicate, object $entity, ?object $edge = null)
    {
        $this
            ->setPredicate($predicate)
            ->setEntity($entity)
            ->setEdge($edge);
    }

    /**
     * @return string
     */
    public function getPredicate(): string
    {
        return $this->predicate;
    }

    /**
     * @param string $predicate
     * @return RelatedEntity
     */
    public function setPredicate(string $predicate): RelatedEntity
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @param object $entity
     * @return RelatedEntity
     */
    public function setEntity(object $entity): RelatedEntity
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return object|null
     */
    public function getEdge(): ?object
    {
        return $this->edge;
    }

    /**
     * @param object|null $edge
     * @return RelatedEntity
     */
    public function setEdge(?object $edge): RelatedEntity
    {
        $this->edge = $edge;
        return $this;
    }
}
