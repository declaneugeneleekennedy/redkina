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
     * @var DateTime
     * @Redkina\Property\Timestamp()
     */
    protected $dateOfBirth;

    /**
     * @var string|null
     *
     * @NotRedkina\Fake()
     */
    protected $ignored;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Person
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

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
    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * @param DateTime $dateOfBirth
     * @return Person
     */
    public function setDateOfBirth(DateTime $dateOfBirth): Person
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }
}
