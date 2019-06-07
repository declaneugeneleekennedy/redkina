<?php

namespace DevDeclan\Redkina\Generator;

interface KeyInterface
{
    public function generate(string $entityName, string $id): string;
}
