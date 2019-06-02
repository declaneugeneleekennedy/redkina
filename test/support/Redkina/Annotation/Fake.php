<?php

namespace DevDeclan\Test\Support\Redkina\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation class which doesn't "belong" to Redkina. Used for testing that those will be ignored when extracting
 * metadata from a Redkina entity model.
 *
 * @package DevDeclan\Test\Support\Redkina\Annotation
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Fake
{
}
