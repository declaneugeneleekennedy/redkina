<?php

namespace DevDeclan\Test\Unit\Redkina\Storage;

use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\TripleEntity;
use DevDeclan\Redkina\Storage\TripleSet;
use DevDeclan\Test\Unit\Redkina\TripleTestCase;
use InvalidArgumentException;

class TripleSetTest extends TripleTestCase
{
    public function testHappyPathGetKeys()
    {
        $tripleSet = new TripleSet($this->happyPathTriple);

        $sort = function (array $in) {
            sort($in);

            return $in;
        };

        $generatedKeys = $sort($tripleSet->getKeys());
        $expectedKeys = $sort($this->keys);

        $this->assertEquals($expectedKeys, $generatedKeys);
    }

    public function testHappyPathGetQueryHavingSubject()
    {
        $subject = new TripleEntity('Foo', '123');
        $predicate = 'is_admirer_of';

        $triple = (new Triple())
            ->setPredicate($predicate)
            ->setSubject($subject);

        $tripleSet = new TripleSet($triple);

        $this->assertEquals('spo:Foo.123:is_admirer_of:', $tripleSet->getQuery('spo'));
    }

    public function testHappyPathGetQueryHavingObject()
    {
        $object = new TripleEntity('Bar', '321');
        $predicate = 'is_admirer_of';

        $triple = (new Triple())
            ->setPredicate($predicate)
            ->setObject($object);

        $tripleSet = new TripleSet($triple);

        $this->assertEquals('ops:Bar.321:is_admirer_of:', $tripleSet->getQuery('ops'));
    }

    public function testHappyPathGetQueryGuessingOrderingHavingSubject()
    {
        $subject = new TripleEntity('Foo', '123');
        $predicate = 'is_admirer_of';

        $triple = (new Triple())
            ->setPredicate($predicate)
            ->setSubject($subject);

        $tripleSet = new TripleSet($triple);

        $this->assertEquals('spo:Foo.123:is_admirer_of:', $tripleSet->getQuery());
    }

    public function testHappyPathGetQueryGuessingOrderingHavingObject()
    {
        $object = new TripleEntity('Bar', '321');
        $predicate = 'is_admirer_of';

        $triple = (new Triple())
            ->setPredicate($predicate)
            ->setObject($object);

        $tripleSet = new TripleSet($triple);

        $this->assertEquals('ops:Bar.321:is_admirer_of:', $tripleSet->getQuery());
    }

    public function testBadGetQueryHavingCompletedTriple()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Impossible to guess ordering of query as the provided triple is already complete'
        );

        $tripleSet = new TripleSet($this->happyPathTriple);
        $tripleSet->getQuery();
    }

    public function testBadGetQueryHavingIncompleteTriple()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Neither the object nor the subject has an ID so there is nothing to relate them to'
        );

        $triple = clone $this->happyPathTriple;

        $triple->getSubject()->setId('');
        $triple->getObject()->setId('');

        $tripleSet = new TripleSet($this->happyPathTriple);
        $tripleSet->getQuery();
    }
}
