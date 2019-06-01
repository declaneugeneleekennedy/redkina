<?php

namespace DevDeclan\Redkina\Annotation;

use DevDeclan\Redkina\AnnotationInterface;
use Doctrine\Common\Annotations\Annotation;

/**
 * Model annotation handler
 *
 * @package DevDeclan\Redkina\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class Entity implements AnnotationInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
