<?php

namespace DevDeclan\Redkina\Metadata\Property;

use DevDeclan\Redkina\Metadata\PropertyInterface;
use DevDeclan\Redkina\MetadataInterface;
use DevDeclan\Redkina\Storage\Serializer\Scalar;
use DevDeclan\Redkina\Storage\Unserializer\PassThrough;

class Generic implements MetadataInterface, PropertyInterface
{
    public function getSerializer()
    {
        return Scalar::class;
    }

    public function getUnserializer()
    {
        return PassThrough::class;
    }
}
