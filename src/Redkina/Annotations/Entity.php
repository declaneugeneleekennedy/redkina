<?php

namespace DevDeclan\Redkina\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Model annotation handler
 *
 * @package DevDeclan\Redkina\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class Entity
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
