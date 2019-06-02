<?php

namespace DevDeclan\Redkina\Relationship;

class Connectable
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
     * @param string $id
     * @return Connectable
     */
    public function setId(string $id): Connectable
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
     * @param string $name
     * @return Connectable
     */
    public function setName(string $name): Connectable
    {
        $this->name = $name;
        return $this;
    }
}
