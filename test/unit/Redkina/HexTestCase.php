<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\Relationship\Connectable;
use DevDeclan\Redkina\Relationship\Relationship;
use PHPUnit\Framework\TestCase;

abstract class HexTestCase extends TestCase
{
    protected $orderings = [
        'spo',
        'pos',
        'osp',
        'ops',
        'pso',
        'sop',
    ];

    protected $keys = [
        'spo:Foo.123:is_admirer_of:Bar.321',
        'pos:is_admirer_of:Bar.321:Foo.123',
        'osp:Bar.321:Foo.123:is_admirer_of',
        'ops:Bar.321:is_admirer_of:Foo.123',
        'pso:is_admirer_of:Foo.123:Bar.321',
        'sop:Foo.123:Bar.321:is_admirer_of',
    ];

    protected $happyPathRelationship;

    public function setUp(): void
    {
        parent::setUp();

        $this->happyPathRelationship = (new Relationship())
            ->setSubject((new Connectable())
                ->setName('Foo')
                ->setId('123'))
            ->setPredicate('is_admirer_of')
            ->setObject((new Connectable())
                ->setName('Bar')
                ->setId('321'));
    }
}
