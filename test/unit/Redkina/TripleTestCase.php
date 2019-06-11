<?php

namespace DevDeclan\Test\Unit\Redkina;

use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\TripleEntity;
use PHPUnit\Framework\TestCase;

abstract class TripleTestCase extends TestCase
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

    /**
     * @var Triple
     */
    protected $happyPathTriple;

    public function setUp(): void
    {
        parent::setUp();

        $this->happyPathTriple = (new Triple())
            ->setSubject(new TripleEntity('Foo', '123'))
            ->setPredicate('is_admirer_of')
            ->setObject(new TripleEntity('Bar', '321'));
    }
}
