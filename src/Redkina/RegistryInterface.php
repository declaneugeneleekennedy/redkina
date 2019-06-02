<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Metadata\Entity as EntityMetadata;

interface RegistryInterface
{
    public function getClassName(string $type): ? string;
    public function getEntityName(string $className): ? string;
    public function getClassMetadata(string $className): ? EntityMetadata;
    public function getEntityMetadata(string $entityName): ? EntityMetadata;
}
