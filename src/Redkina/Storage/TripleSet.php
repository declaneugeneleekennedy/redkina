<?php

namespace DevDeclan\Redkina\Storage;

use InvalidArgumentException;

class TripleSet
{
    const SUBJECT = 's';

    const OBJECT = 'o';

    const PREDICATE = 'p';

    protected $combinations = [
        [self::SUBJECT, self::PREDICATE, self::OBJECT],
        [self::SUBJECT, self::OBJECT, self::PREDICATE],
        [self::PREDICATE, self::SUBJECT, self::OBJECT],
        [self::PREDICATE, self::OBJECT, self::SUBJECT],
        [self::OBJECT, self::PREDICATE, self::SUBJECT],
        [self::OBJECT, self::SUBJECT, self::PREDICATE],
    ];

    /**
     * @var Triple
     */
    protected $triple;

    /**
     * @param Triple $triple
     */
    public function __construct(Triple $triple)
    {
        $this->triple = $triple;
    }

    /**
     * @return string[]
     */
    public function getKeys()
    {
        $hexKey = new TripleKey($this->triple);

        $keys = [];

        foreach ($this->combinations as $combination) {
            $keys[] = $hexKey->format(implode('', $combination));
        }

        return $keys;
    }

    /**
     * @param string|null $ordering
     * @return string
     */
    public function getQuery(?string $ordering = null): string
    {
        if (is_null($ordering)) {
            $ordering = $this->guessOrdering();
        }

        return (new TripleKey($this->triple))->format($ordering);
    }

    /**
     * "Guess" how to generate a lexicographical query based on the instance "triple". In this situation, "guess" can be
     * interpreted as:
     * - If we have a complete subject and a complete object (i.e. each consisting of a name and id) then throw, because
     * it's impossible to guess whether the library implementer intended to query one or the other
     * - If we have neither a complete subject or a complete object then throw, because again it's not possible to infer
     * the intent of the implementer
     * - If we have a predicate, and either a subject or object which has both a name and id, assume that's the root of
     * the query the implementer wanted us to generate
     *
     * @return string
     */
    protected function guessOrdering()
    {
        if (($this->triple->hasSubject() && $this->triple->getSubject()->getId()) &&
            ($this->triple->hasObject() && $this->triple->getObject()->getId())
        ) {
            throw new InvalidArgumentException(
                'Impossible to guess ordering of query as the provided triple is already complete'
            );
        }

        if (($this->triple->hasSubject() && !$this->triple->getSubject()->getId()) &&
            ($this->triple->hasObject() && !$this->triple->getObject()->getId())
        ) {
            throw new InvalidArgumentException(
                'Neither the object nor the subject has an ID so there is nothing to relate them to'
            );
        }

        if ($this->triple->hasSubject() && $this->triple->getSubject()->getId()) {
            return 'spo';
        }

        return 'ops';
    }
}
