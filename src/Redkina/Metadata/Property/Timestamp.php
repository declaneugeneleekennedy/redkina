<?php

namespace DevDeclan\Redkina\Metadata\Property;

use DevDeclan\Redkina\Metadata\PropertyInterface;
use DevDeclan\Redkina\MetadataInterface;
use DevDeclan\Redkina\Storage\Serializer\DateTime;
use DevDeclan\Redkina\Storage\Unserializer\ISO8601;

class Timestamp implements MetadataInterface, PropertyInterface
{
    public function getSerializer()
    {
        return DateTime::class;
    }

    public function getUnserializer()
    {
        return ISO8601::class;
    }
}
