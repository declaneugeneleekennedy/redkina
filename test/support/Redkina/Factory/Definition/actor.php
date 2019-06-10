<?php

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use DevDeclan\Test\Support\Redkina\Entity\Actor;

/** @var FactoryMuffin $fm */
$fm->define(Actor::class)->setDefinitions([
    'firstName' => Faker::firstName(),
    'lastName' => Faker::lastName(),
]);
