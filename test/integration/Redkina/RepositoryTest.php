<?php

namespace DevDeclan\Test\Integration\Redkina;

use DevDeclan\Redkina\RelatedEntity;
use DevDeclan\Test\Support\Redkina\Entity\Actor;
use DevDeclan\Test\Support\Redkina\Entity\ActorMovieEdge;
use DevDeclan\Test\Support\Redkina\Entity\Movie;
use DateTime;

class RepositoryTest extends FactoryTestCase
{
    public function testHappyPath()
    {
        $actor = $this->fm->create(Actor::class, [
            'firstName' => 'Keanu',
            'lastName' => 'Reeves'
        ]);

        $movie = $this->fm->create(Movie::class, [
            'title' => 'John Wick',
            'runningTime' => 101,
            'releaseDate' => new DateTime('2014-09-19')
        ]);

        $edge = $this->fm->create(ActorMovieEdge::class, [
            'character' => 'John Wick'
        ]);

        $relatedEntity = new RelatedEntity('appeared_in', $movie, $edge);

        $actor->setAppearedIn([$relatedEntity]);

        $this->repository->save($actor);

        $savedActor = $this->repository->load(Actor::class, $actor->getId());

        $this->assertEquals($actor, $savedActor);
    }
}
