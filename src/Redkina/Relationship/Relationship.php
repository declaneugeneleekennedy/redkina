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
     * @return Connectable
     */
    public function getSubject(): Connectable
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
     * @return Connectable
     */
    public function getObject(): Connectable
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
     * @return string
     */
    public function getPredicate(): string
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
}
