<?php

namespace DevDeclan\Redkina;

interface RegistryInterface
{
    public function getClassName(string $type): ? string;
    public function getEntityName(string $className): ? string;
}
