<?php

namespace DevDeclan\Redkina\Metadata\Property;

use DevDeclan\Redkina\Mapper\Property\Timestamp as Mapper;
use DevDeclan\Redkina\MapperInterface;
use DevDeclan\Redkina\Metadata\PropertyInterface;
use DevDeclan\Redkina\MetadataInterface;

class Timestamp implements MetadataInterface, PropertyInterface
{
    public function getMapper(): MapperInterface
    {
        return new Mapper();
    }
}
