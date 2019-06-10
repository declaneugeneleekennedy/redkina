<?php

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use DevDeclan\Test\Support\Redkina\Entity\Movie;

/** @var FactoryMuffin $fm */
$fm->define(Movie::class)->setDefinitions([
    'title' => Faker::realText(50)
]);
