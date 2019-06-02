<?php

namespace DevDeclan\Redkina\Metadata\Property;

use DevDeclan\Redkina\Mapper\Property\Generic as Mapper;
use DevDeclan\Redkina\MapperInterface;
use DevDeclan\Redkina\Metadata\PropertyInterface;
use DevDeclan\Redkina\MetadataInterface;

class Generic implements MetadataInterface, PropertyInterface
{
    public function getMapper(): MapperInterface
    {
        return new Mapper();
    }
}
