<?php

namespace DevDeclan\Redkina\Storage\Unserializer;

use DevDeclan\Redkina\Storage\UnserializerInterface;

class ISO8601 implements UnserializerInterface
{
    /**
     * @param string $output
     * @return \DateTime|null
     */
    public function unserialize(string $output)
    {
        return date_create($output) ?: null;
    }
}
