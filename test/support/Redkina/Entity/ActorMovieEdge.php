<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

use DevDeclan\Redkina\Annotation as Redkina;

/**
 * Class ActorMovieEdge
 * @package DevDeclan\Test\Support\Redkina\Entity
 *
 * @Redkina\Entity(
 *      name = "ActorMovieEdge"
 * )
 */
class ActorMovieEdge
{
    /**
     * @var string
     * @Redkina\Property\Id();
     */
    protected $id;

    /**
     * @var string
     * @Redkina\Property\Generic()
     */
    protected $character;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return ActorMovieEdge
     */
    public function setId(string $id): ActorMovieEdge
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharacter(): string
    {
        return $this->character;
    }

    /**
     * @param string $character
     * @return ActorMovieEdge
     */
    public function setCharacter(string $character): ActorMovieEdge
    {
        $this->character = $character;
        return $this;
    }
}
