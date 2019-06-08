<?php

namespace DevDeclan\Redkina\Storage;

interface SerializerInterface
{
    public function serialize($input): string;
}
