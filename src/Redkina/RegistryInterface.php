<?php

namespace Declaneugeneleekennedy\Redkina;

interface RegistryInterface
{
    public function getClassName(string $type): ? string;
    public function getType(string $className): ? string;
}
