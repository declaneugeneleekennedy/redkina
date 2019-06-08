<?php

namespace DevDeclan\Redkina\Storage\Unserializer;

use DevDeclan\Redkina\Storage\UnserializerInterface;

class PassThrough implements UnserializerInterface
{
    public function unserialize(string $output)
    {
        return $output;
    }
}
