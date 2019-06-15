<?php

namespace DevDeclan\Test\Integration\Redkina;

use DateTime;
use DevDeclan\Redkina\RelatedEntity;
use DevDeclan\Redkina\Repository;
use DevDeclan\Redkina\Storage\ManagerInterface;
use DevDeclan\Test\Support\Redkina\Entity\ActorMovieEdge;
use DevDeclan\Test\Support\Redkina\Entity\Movie;
use DevDeclan\Test\Support\Redkina\Entity\Person\Actor;
use League\FactoryMuffin\Exceptions\DeletingFailedException;
use Prophecy\Argument;

class RepositoryTest extends FactoryTestCase
{
    /**
     * @var Actor
     */
    protected $actor;

    /**
     * @var Movie
     */
    protected $movie;

    /**
     * @var ActorMovieEdge
     */
    protected $edge;

    public function setUp(): void
    {
        parent::setUp();

        $this->actor = $this->fm->create(Actor::class, [
            'firstName' => 'Keanu',
            'lastName' => 'Reeves'
        ]);

        $this->movie = $this->fm->create(Movie::class, [
            'title' => 'John Wick',
            'runningTime' => 101,
            'releaseDate' => new DateTime('2014-09-19')
        ]);

        $this->edge = $this->fm->create(ActorMovieEdge::class, [
            'character' => 'John Wick'
        ]);
    }

    /**
     * @throws \Exception
     */
    public function testHappyPathWithActor()
    {
        $actor = clone $this->actor;

        $relatedEntity = new RelatedEntity('appeared_in', $this->movie, $this->edge);

        $actor->setAppearedIn([$relatedEntity]);

        $this->repository->save($actor);

        $saved = $this->repository->load(Actor::class, $actor->getId());

        $this->assertEquals($actor, $saved);
    }

    /**
     * @throws \Exception
     */
    public function testHappyPathWithMovie()
    {
        $movie = clone $this->movie;

        $relatedEntity = new RelatedEntity('appeared_in', $this->actor, $this->edge);

        $movie->setActors([$relatedEntity]);

        $this->repository->save($movie);

        $saved = $this->repository->load(Movie::class, $movie->getId());

        $this->assertEquals($movie, $saved);
    }

    public function testBadSaveFailed()
    {
        $manager = $this->prophesize(ManagerInterface::class);

        $manager->save('Actor', Argument::type('array'))->willReturn(null);

        $repository = new Repository($this->registry, $manager->reveal());

        $this->assertNull($repository->save($this->actor));
    }

    public function testBadLoadNotFound()
    {
        $this->assertNull($this->repository->load(Actor::class, 'this-is-not-a-real-id'));
    }

    public function testBadRelatedEntityNotFound()
    {
        $actor = clone $this->actor;

        $relatedEntity = new RelatedEntity('appeared_in', $this->movie, $this->edge);

        $actor->setAppearedIn([$relatedEntity]);

        $this->repository->save($actor);

        $this->store->delete($this->movie);
        $actor->setAppearedIn([]);

        $saved = $this->repository->load(Actor::class, $actor->getId());

        $this->assertEquals($actor, $saved);

        /**
         * This is a smelly way to make the test pass but also make a small effort to ensure that it won't obscure any
         * actual errors. Because the test deletes the movie it also deletes the relationship between the actor and the
         * movie, which causes the edge to also be removed. Hence, we expect FM will fail to remove 2 models it stored.
         */
        $this->expectException(DeletingFailedException::class);
        $this->expectExceptionMessage('We encountered 2 problems while trying to delete the saved models.');

        $this->fm->deleteSaved();
    }
}
