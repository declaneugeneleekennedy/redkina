<?php

namespace DevDeclan\Test\Support\Redkina\Entity;

/**
 * This class is here to make sure that Redkina doesn't try to add classes to the registry which aren't actually Redkina
 * compatible entities, which it determines using annotations (or the absence thereof).
 *
 * @package DevDeclan\Test\Support\Redkina\Entity
 */
class Fake
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string $id
     * @return Fake
     */
    public function setId(string $id): Fake
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return Fake
     */
    public function setName(string $name): Fake
    {
        $this->name = $name;
        return $this;
    }
}
