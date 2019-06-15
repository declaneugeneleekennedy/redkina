<?php

use DevDeclan\Test\Support\Redkina\Entity\Person\Actor;
use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;

/** @var FactoryMuffin $fm */
$fm->define(Actor::class)->setDefinitions([
    'firstName' => Faker::firstName(),
    'lastName' => Faker::lastName(),
    'dateOfBirth' => Faker::dateTime()
]);
