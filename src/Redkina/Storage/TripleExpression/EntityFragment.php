<?php


namespace DevDeclan\Redkina\Storage\TripleExpression;

use DevDeclan\Redkina\Storage\TripleBuilder;
use DevDeclan\Redkina\Storage\TripleEntity;

class EntityFragment
{
    /**
     * @var TripleEntity
     */
    protected $tripleEntity;

    /**
     * @var string
     */
    protected $role;

    public function __construct(TripleEntity $tripleEntity)
    {
        $this->tripleEntity = $tripleEntity;
    }

    public function asThe(string $role)
    {
        $this->role = $role;

        return $this;
    }

    public function inverse()
    {
        $this->role = $this->role === 'subject' ? 'object' : 'subject';

        return $this;
    }

    public function forTripleIn(TripleBuilder $tripleBuilder): TripleBuilder
    {
        $triple = $tripleBuilder->getTriple();
        if ($this->role === 'subject') {
            $triple->setSubject($this->tripleEntity);
        } else {
            $triple->setObject($this->tripleEntity);
        }

        return $tripleBuilder;
    }
}
