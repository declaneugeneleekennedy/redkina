<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

use DevDeclan\Redkina\Annotation as Redkina;
use DateTime;

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
     * @var integer
     * @Redkina\Property\Integer()
     */
    protected $runningTime;

    /**
     * @var DateTime
     * @Redkina\Property\Timestamp()
     */
    protected $releaseDate;

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
     * @return string|null
     */
    public function getId(): ?string
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
     * @return int
     */
    public function getRunningTime(): int
    {
        return $this->runningTime;
    }

    /**
     * @param int $runningTime
     * @return Movie
     */
    public function setRunningTime(int $runningTime): Movie
    {
        $this->runningTime = $runningTime;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getReleaseDate(): DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @param DateTime $releaseDate
     * @return Movie
     */
    public function setReleaseDate(DateTime $releaseDate): Movie
    {
        $this->releaseDate = $releaseDate;
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
}
