<?php

namespace DevDeclan\Redkina\Storage\Serializer;

use DevDeclan\Redkina\Storage\SerializerInterface;

class Scalar implements SerializerInterface
{
    public function serialize($input): string
    {
        return (string) $input;
    }
}
