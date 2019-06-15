<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\Metadata;
use DevDeclan\Redkina\MetadataExtractor;
use DevDeclan\Redkina\PropertyMetadataFactory;
use DevDeclan\Test\Support\Redkina\Entity\ActorMovieEdge;
use DevDeclan\Test\Support\Redkina\Entity\Fake;
use DevDeclan\Test\Support\Redkina\Entity\Movie;
use DevDeclan\Test\Support\Redkina\Entity\Person\Actor;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MetadataExtractorTest extends TestCase
{
    /**
     * @var ReflectionClass
     */
    protected $reflected;

    /**
     * @var MetadataExtractor
     */
    protected $extractor;

    public function setUp(): void
    {
        parent::setUp();

        $this->extractor = new MetadataExtractor(new AnnotationReader(), new PropertyMetadataFactory());
    }

    /**
     * @dataProvider happyPathDataProvider
     *
     * @param string $className
     * @param Metadata\Entity $expected
     * @throws \ReflectionException
     */
    public function testHappyPath(string $className, ?Metadata\Entity $expected)
    {
        $reflected = new ReflectionClass($className);

        $this->assertEquals($expected, $this->extractor->extract($reflected));
    }

    public function happyPathDataProvider()
    {
        return [
            [Actor::class, $this->getExpectedEntityMetadata()],
            [Movie::class, $this->getExpectedMovieMetadata()],
            [ActorMovieEdge::class, $this->getExpectedEdgeData()],
            [Fake::class, null]
        ];
    }

    protected function getExpectedEntityMetadata()
    {
        return (new Metadata\Entity())
            ->setClassName(Actor::class)
            ->setName('Actor')
            ->addProperty('id', new Metadata\Property\Id())
            ->addProperty('firstName', new Metadata\Property\Generic())
            ->addProperty('lastName', new Metadata\Property\Generic())
            ->addProperty('dateOfBirth', new Metadata\Property\Timestamp())
            ->addRelationship('appearedIn', new Metadata\Relationship(
                'appeared_in',
                'subject',
                'Movie'
            ));
    }

    protected function getExpectedMovieMetadata()
    {
        return (new Metadata\Entity())
            ->setClassName(Movie::class)
            ->setName('Movie')
            ->addProperty('id', new Metadata\Property\Id())
            ->addProperty('title', new Metadata\Property\Generic())
            ->addProperty('runningTime', new Metadata\Property\Integer())
            ->addProperty('releaseDate', new Metadata\Property\Timestamp())
            ->addRelationship('actors', new Metadata\Relationship(
                'appeared_in',
                'object',
                'Actor'
            ));
    }

    protected function getExpectedEdgeData()
    {
        return (new Metadata\Entity())
            ->setClassName(ActorMovieEdge::class)
            ->setName('ActorMovieEdge')
            ->addProperty('id', new Metadata\Property\Id())
            ->addProperty('character', new Metadata\Property\Generic());
    }
}
