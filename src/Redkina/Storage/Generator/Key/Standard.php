<?php

namespace DevDeclan\Redkina\Storage\Generator\Key;

use DevDeclan\Redkina\Storage\Generator\KeyInterface;

class Standard implements KeyInterface
{
    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @param string $delimiter
     */
    public function __construct(string $delimiter = '.')
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @param string $entityName
     * @param string $id
     * @return string
     */
    public function generate(string $entityName, string $id): string
    {
        return $entityName . $this->delimiter . $id;
    }
}
