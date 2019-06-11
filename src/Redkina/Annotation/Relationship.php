<?php

namespace DevDeclan\Redkina\Annotation;

use DevDeclan\Redkina\AnnotationInterface;

/**
 * @package DevDeclan\Redkina\Annotation\Property
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Relationship implements AnnotationInterface
{
    /**
     * @var string
     */
    public $predicate;

    /**
     * @var string
     */
    public $role;

    /**
     * @var string
     */
    public $entityType;

    /**
     * @return string
     */
    public function getPredicate(): string
    {
        return $this->predicate;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getEntityType(): ?string
    {
        return $this->entityType;
    }
}
