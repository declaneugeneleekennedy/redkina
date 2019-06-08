<?php

namespace DevDeclan\Redkina\Storage\Generator;

interface KeyInterface
{
    public function generate(string $entityName, string $id): string;
}
