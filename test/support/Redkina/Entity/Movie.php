<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

use DevDeclan\Redkina\Annotation as Redkina;

/**
 * @package DevDeclan\Test\Support\Redkina\Entity
 * @Redkina\Entity(
 *     name = "Movie"
 * )
 */
class Movie
{
    /**
     * @var string
     * @Redkina\Property\Id()
     */
    protected $id;

    /**
     * @var string
     * @Redkina\Property\Generic()
     */
    protected $title;

    /**
     * @var array
     * @Redkina\Relationship(
     *     predicate = "appeared_in",
     *     role = "object",
     *     entityType = "Actor"
     * )
     */
    protected $actors = [];

    /**
     * @var array
     * @Redkina\Relationship(
     *     predicate = "directed",
     *     role = "object",
     *     entityType = "Actor"
     * )
     */
    protected $directors = [];

    /**
     * @return string|null
     */
    public function getId(): ? string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Movie
     */
    public function setId(string $id): Movie
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Movie
     */
    public function setTitle(string $title): Movie
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getActors(): array
    {
        return $this->actors;
    }

    /**
     * @param array $actors
     * @return Movie
     */
    public function setActors(array $actors): Movie
    {
        $this->actors = $actors;
        return $this;
    }

    /**
     * @return array
     */
    public function getDirectors(): array
    {
        return $this->directors;
    }

    /**
     * @param array $directors
     * @return Movie
     */
    public function setDirectors(array $directors): Movie
    {
        $this->directors = $directors;
        return $this;
    }
}
