<?php

namespace DevDeclan\Redkina\Metadata;

use DevDeclan\Redkina\MetadataInterface;

/**
 * @package DevDeclan\Redkina\Metadata
 */
class Relationship implements MetadataInterface
{
    /**
     * @var string
     */
    const ROLE_SUBJECT = 'subject';

    /**
     * @var string
     */
    const ROLE_OBJECT = 'object';

    /**
     * @var string
     */
    protected $predicate;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var string
     */
    protected $entityType;

    /**
     * @param string $predicate
     * @param string|null $role
     * @param string|null $entityType
     */
    public function __construct(string $predicate, ?string $role = null, ?string $entityType = null)
    {
        $this
            ->setPredicate($predicate)
            ->setRole($role ?? self::ROLE_SUBJECT)
            ->setEntityType($entityType);
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
     * @return Relationship
     */
    public function setPredicate(string $predicate): Relationship
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return Relationship
     */
    public function setRole(string $role): Relationship
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    /**
     * @param string $entityType
     * @return Relationship
     */
    public function setEntityType(string $entityType): Relationship
    {
        $this->entityType = $entityType;
        return $this;
    }
}
