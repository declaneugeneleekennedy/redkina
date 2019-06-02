<?php

namespace DevDeclan\Redkina\Metadata\Property;

use DevDeclan\Redkina\MapperInterface;
use DevDeclan\Redkina\Mapper\Property\Integer as Mapper;
use DevDeclan\Redkina\Metadata\PropertyInterface;
use DevDeclan\Redkina\MetadataInterface;

class Integer implements MetadataInterface, PropertyInterface
{
    public function getMapper(): MapperInterface
    {
        return new Mapper();
    }
}
