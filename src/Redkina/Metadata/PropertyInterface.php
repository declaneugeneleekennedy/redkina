<?php

namespace DevDeclan\Redkina\Metadata;

use DevDeclan\Redkina\MapperInterface;

interface PropertyInterface
{
    public function getMapper(): MapperInterface;
}
