<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

use DevDeclan\Test\Support\Redkina\Annotation as NotRedkina;
use DevDeclan\Redkina\Annotation as Redkina;
use DateTime;

/**
 * Generic person Entity for tests
 *
 * @package DevDeclan\Test\Support\Model
 *
 * @Redkina\Entity(
 *     name = "Person"
 * )
 */
class Person
{
    /**
     * @var string
     *
     * @Redkina\Property\Id()
     */
    protected $id;

    /**
     * @var string
     *
     * @Redkina\Property\Generic()
     */
    protected $firstName;

    /**
     * @var string
     *
     * @Redkina\Property\Generic()
     */
    protected $lastName;

    /**
     * @var int
     *
     * @Redkina\Property\Integer()
     */
    protected $age;

    /**
     * @var DateTime
     *
     * @Redkina\Property\Timestamp()
     */
    protected $created;

    /**
     * @var DateTime
     *
     * @Redkina\Property\Timestamp()
     */
    protected $updated;

    /**
     * @var string|null
     *
     * @NotRedkina\Fake()
     */
    protected $ignored;

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
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param  DateTime $created
     * @return Person
     */
    public function setCreated(DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdated(): DateTime
    {
        return $this->updated;
    }

    /**
     * @param  DateTime $updated
     * @return Person
     */
    public function setUpdated(DateTime $updated): self
    {
        $this->updated = $updated;
        return $this;
    }
}
