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
     * @var array
     */
    public $entityTypes = [];

    /**
     * @var string
     */
    public $predicate;

    /**
     * @return array
     */
    public function getEntityTypes(): array
    {
        return $this->entityTypes;
    }

    /**
     * @return string|null
     */
    public function getPredicate(): ? string
    {
        return $this->predicate;
    }
}
