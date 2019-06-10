<?php

namespace DevDeclan\Redkina\Storage;

class Triple
{
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
     * @var TripleEntity
     */
    protected $edge;

    /**
     * @return TripleEntity|null
     */
    public function getEdge(): ? TripleEntity
    {
        return $this->edge;
    }

    /**
     * @param TripleEntity|null $edge
     * @return Triple
     */
    public function setEdge(? TripleEntity $edge): Triple
    {
        $this->edge = $edge;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasEdge(): bool
    {
        return !(empty($this->edge));
    }

    /**
     * @return TripleEntity|null
     */
    public function getSubject(): ? TripleEntity
    {
        return $this->subject;
    }

    /**
     * @param TripleEntity $subject
     * @return Triple
     */
    public function setSubject(TripleEntity $subject): Triple
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSubject(): bool
    {
        return !(empty($this->subject));
    }

    /**
     * @return TripleEntity|null
     */
    public function getObject(): ? TripleEntity
    {
        return $this->object;
    }

    /**
     * @param TripleEntity $object
     * @return Triple
     */
    public function setObject(TripleEntity $object): Triple
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasObject(): bool
    {
        return !(empty($this->object));
    }

    /**
     * @return string|null
     */
    public function getPredicate(): ? string
    {
        return $this->predicate;
    }

    /**
     * @param string $predicate
     * @return Triple
     */
    public function setPredicate(string $predicate): Triple
    {
        $this->predicate = $predicate;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPredicate(): bool
    {
        return !(empty($this->predicate));
    }
}
