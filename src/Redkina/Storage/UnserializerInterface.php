<?php

namespace DevDeclan\Redkina\Storage;

interface UnserializerInterface
{
    public function unserialize(string $output);
}
