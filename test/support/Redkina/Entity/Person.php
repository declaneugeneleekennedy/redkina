<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

use DevDeclan\Redkina\Entity;
use DevDeclan\Redkina\Annotation as Redkina;

/**
 * Generic person Entity for tests
 *
 * @package         DevDeclan\Test\Support\Model
 * @Redkina\Entity(
 *     name = "Person"
 * )
 */
class Person extends Entity
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var                        int
     * @Redkina\Property\Integer()
     */
    protected $age;

    /**
     * @var string
     */
    protected $created;

    /**
     * @var string
     */
    protected $updated;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param  string $firstName
     * @return Person
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param  string $lastName
     * @return Person
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * @param  string $created
     * @return Person
     */
    public function setCreated(string $created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdated(): string
    {
        return $this->updated;
    }

    /**
     * @param  string $updated
     * @return Person
     */
    public function setUpdated($updated): self
    {
        $this->updated = $updated;
        return $this;
    }
}
