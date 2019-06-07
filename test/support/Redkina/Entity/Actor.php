<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

use DevDeclan\Redkina\Annotation as Redkina;

/**
 * Class Actor
 * @package DevDeclan\Test\Support\Redkina\Entity
 *
 * @Redkina\Entity(
 *     name = "Actor"
 * )
 */
class Actor
{
    /**
     * @var string
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
     * @var array
     *
     * @Redkina\Property\Relationship(
     *     entityTypes = {"Movie"},
     *     predicate = "appeared_in"
     * )
     */
    protected $appearedIn;

    /**
     * @var array
     *
     * @Redkina\Property\Relationship(
     *     entityTypes = {"Movie"},
     *     predicate = "directed"
     * )
     */
    protected $directed;

    /**
     * @var array
     *
     * @Redkina\Property\Relationship(
     *     entityTypes = {"Movie"}
     * )
     */
    protected $movies;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Actor
     */
    public function setId(string $id): Actor
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
     * @param string $firstName
     * @return Actor
     */
    public function setFirstName(string $firstName): Actor
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
     * @param string $lastName
     * @return Actor
     */
    public function setLastName(string $lastName): Actor
    {
        $this->lastName = $lastName;
        return $this;
    }

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

    /**
     * @return array
     */
    public function getDirected(): array
    {
        return $this->directed;
    }

    /**
     * @param array $directed
     * @return Actor
     */
    public function setDirected(array $directed): Actor
    {
        $this->directed = $directed;
        return $this;
    }

    /**
     * @return array
     */
    public function getMovies(): array
    {
        return $this->movies;
    }

    /**
     * @param array $movies
     * @return Actor
     */
    public function setMovies(array $movies): Actor
    {
        $this->movies = $movies;
        return $this;
    }
}
