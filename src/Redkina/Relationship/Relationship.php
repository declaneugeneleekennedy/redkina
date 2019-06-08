<?php

namespace DevDeclan\Redkina\Relationship;

class Relationship
{
    /**
     * @var Connectable
     */
    protected $subject;

    /**
     * @var Connectable
     */
    protected $object;

    /**
     * @var string
     */
    protected $predicate;

    /**
     * @var Connectable
     */
    protected $edge;

    /**
     * @return Connectable|null
     */
    public function getEdge(): ? Connectable
    {
        return $this->edge;
    }

    /**
     * @param Connectable|null $edge
     * @return Relationship
     */
    public function setEdge(? Connectable $edge): Relationship
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
     * @return Connectable|null
     */
    public function getSubject(): ? Connectable
    {
        return $this->subject;
    }

    /**
     * @param Connectable $subject
     * @return Relationship
     */
    public function setSubject(Connectable $subject): Relationship
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
     * @return Connectable|null
     */
    public function getObject(): ? Connectable
    {
        return $this->object;
    }

    /**
     * @param Connectable $object
     * @return Relationship
     */
    public function setObject(Connectable $object): Relationship
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
     * @return Relationship
     */
    public function setPredicate(string $predicate): Relationship
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
