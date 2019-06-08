<?php

namespace DevDeclan\Redkina\Storage\Unserializer;

use DevDeclan\Redkina\Storage\UnserializerInterface;

class Number implements UnserializerInterface
{
    public function unserialize(string $output)
    {
        return floatval($output);
    }
}
