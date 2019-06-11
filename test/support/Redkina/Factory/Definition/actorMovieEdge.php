<?php

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use DevDeclan\Test\Support\Redkina\Entity\ActorMovieEdge;

/** @var FactoryMuffin $fm */
$fm->define(ActorMovieEdge::class)->setDefinitions([
    'character' => Faker::name(),
]);
