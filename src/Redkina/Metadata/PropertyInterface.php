<?php

namespace DevDeclan\Redkina\Metadata;

interface PropertyInterface
{
    public function getSerializer();
    public function getUnserializer();
}
