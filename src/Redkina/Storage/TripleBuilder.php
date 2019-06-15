<?php

namespace DevDeclan\Redkina\Storage;

use DevDeclan\Redkina\Storage\TripleExpression\EntityFragment;

class TripleBuilder
{
    const SUBJECT = 'subject';

    const OBJECT = 'object';

    /**
     * @var TripleEntity
     */
    protected $subject;

    /**
     * @var TripleEntity
     */
    protected $object;

    /**
     * @var string
     */
    protected $predicate;

    /**
     * @var Triple
     */
    protected $triple;

    /**
     * @var TripleEntity
     */
    protected $originator;

    public function with(string $predicate)
    {
        $this->triple = (new Triple())->setPredicate($predicate);

        return $this;
    }

    public function using(string $name, string $id = '')
    {
        return new EntityFragment(new TripleEntity($name, $id));
    }

    public function getTriple(): Triple
    {
        return $this->triple;
    }
}
