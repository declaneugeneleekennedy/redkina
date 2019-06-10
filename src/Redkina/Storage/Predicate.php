<?php

namespace DevDeclan\Redkina\Storage;

class Predicate
{
    const ANY = '*';

    protected $value;

    public function __construct(string $value = self::ANY)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
