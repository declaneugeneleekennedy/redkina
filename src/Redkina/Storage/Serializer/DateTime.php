<?php

namespace DevDeclan\Redkina\Storage\Serializer;

use DevDeclan\Redkina\Storage\SerializerInterface;

class DateTime implements SerializerInterface
{
    /**
     * @param \DateTime $input
     * @return string
     */
    public function serialize($input): string
    {
        return $input->format('c');
    }
}
