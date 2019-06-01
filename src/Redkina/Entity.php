<?php

namespace DevDeclan\Redkina;

use JsonSerializable;

abstract class Entity implements BondableInterface, JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    public function jsonSerialize()
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    public function getId(): ? string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
