<?php

namespace DevDeclan\Redkina;

use DevDeclan\Redkina\Annotation\Property\Generic;
use DevDeclan\Redkina\Annotation\Property\Integer;
use DevDeclan\Redkina\Annotation\Property\Relationship;
use DevDeclan\Redkina\Annotation\Property\Timestamp;
use DevDeclan\Redkina\Annotation\PropertyInterface;
use DevDeclan\Redkina\Metadata\PropertyInterface as PropertyMetadataInterface;
use DevDeclan\Redkina\Metadata\Property\Generic as GenericMetadata;
use DevDeclan\Redkina\Metadata\Property\Integer as IntegerMetadata;
use DevDeclan\Redkina\Metadata\Property\Relationship as RelationshipMetadata;
use DevDeclan\Redkina\Metadata\Property\Timestamp as TimestampMetadata;

class PropertyMetadataFactory
{
    public function getByAnnotation(PropertyInterface $annotation): PropertyMetadataInterface
    {
        switch (get_class($annotation)) {
            case Relationship::class:
                /** @var Relationship $annotation */
                return new RelationshipMetadata($annotation->getEntityTypes(), $annotation->getPredicate());
            case Integer::class:
                return new IntegerMetadata();
            case Timestamp::class:
                return new TimestampMetadata();
            case Generic::class:
            default:
                return new GenericMetadata();
        }
    }
}
