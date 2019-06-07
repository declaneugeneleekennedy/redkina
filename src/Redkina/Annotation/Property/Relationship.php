<?php

namespace DevDeclan\Redkina\Annotation\Property;

use DevDeclan\Redkina\AnnotationInterface;
use DevDeclan\Redkina\Annotation\PropertyInterface;
use Doctrine\Common\Annotations\Annotation;

/**
 * @package DevDeclan\Redkina\Annotation\Property
 *
 * @Annotation
 */
class Relationship implements AnnotationInterface, PropertyInterface
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
