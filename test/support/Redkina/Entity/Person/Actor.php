<?php

namespace DevDeclan\Test\Support\Redkina\Entity\Person;

use DevDeclan\Redkina\Annotation as Redkina;
use DevDeclan\Test\Support\Redkina\Entity\Person;

/**
 * Class Actor
 * @package DevDeclan\Test\Support\Redkina\Entity
 *
 * @Redkina\Entity(
 *     name = "Actor"
 * )
 */
class Actor extends Person
{
    /**
     * @var array
     *
     * @Redkina\Relationship(
     *     entityType = "Movie",
     *     predicate = "appeared_in"
     * )
     */
    protected $appearedIn = [];

    /**
     * @return array
     */
    public function getAppearedIn(): array
    {
        return $this->appearedIn;
    }

    /**
     * @param array $appearedIn
     * @return Actor
     */
    public function setAppearedIn(array $appearedIn): Actor
    {
        $this->appearedIn = $appearedIn;
        return $this;
    }
}
