<?php

namespace DevDeclan\Redkina\Relationship;

use InvalidArgumentException;

class Hexastore
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
     * @var Relationship
     */
    protected $relationship;

    /**
     * @param Relationship $relationship
     */
    public function __construct(Relationship $relationship)
    {
        $this->relationship = $relationship;
    }

    /**
     * @return string[]
     */
    public function getKeys()
    {
        $hexKey = new HexKey($this->relationship);

        $keys = [];

        foreach ($this->combinations as $combination) {
            $keys[] = $hexKey->format(implode('', $combination));
        }

        return $keys;
    }

    /**
     * @param string $ordering
     * @return string
     */
    public function getQuery(string $ordering = 'spo'): string
    {
        return (new HexKey($this->relationship))->format($ordering);
    }
}
