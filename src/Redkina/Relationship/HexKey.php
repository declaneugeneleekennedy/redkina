<?php

namespace DevDeclan\Redkina\Relationship;

use InvalidArgumentException;

/**
 * @package DevDeclan\Redkina\Relationship
 */
class HexKey
{
    /**
     * Regex to test that an ordering is exactly 3 characters and only includes s, p, and o
     */
    const ORDERING_REGEX = '/^[spo]{1}[spo]{1}[spo]{1}$/i';

    /**
     * Delimiter for the 4 parts of a hex key
     */
    const HEX_KEY_DELIMITER = ':';

    /**
     * Delimiter for the entity and ID keys of a hex key
     */
    const ENTITY_KEY_DELIMITER = '.';

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
     * Outputs a hexastore key with the provided ordering
     *
     * @param string $ordering
     * @return string
     */
    public function format(string $ordering): string
    {
        if (strlen($ordering) !== 3) {
            throw new InvalidArgumentException(
                'Ordering string length is less than 3'
            );
        }

        if (preg_match(self::ORDERING_REGEX, $ordering) === 0) {
            throw new InvalidArgumentException('Invalid ordering string: ' . $ordering);
        }

        $parts = [$ordering];
        for ($i = 0; $i < 3; ++$i) {
            $char = substr($ordering, $i, 1);

            if ($char === 'p') {
                $parts[] = $this->relationship->getPredicate();
                continue;
            }

            /**
             * @var Connectable $target
             */
            $target = ($char === 's') ? $this->relationship->getSubject() : $this->relationship->getObject();

            if (is_null($target)) {
                $parts[] = '';
            } else {
                $parts[] = $target->getName() . self::ENTITY_KEY_DELIMITER . $target->getId();
            }
        }

        return implode(self::HEX_KEY_DELIMITER, $parts);
    }

    /**
     * Will reconstitute a relationship object from a hexastore key
     *
     * @param string $key
     * @return Relationship
     */
    public static function hydrate(string $key)
    {
        $parts = explode(self::HEX_KEY_DELIMITER, $key);

        if (count($parts) !== 4) {
            throw new InvalidArgumentException('Hex key could not be parsed: ' . $key);
        }

        $ordering = $parts[0];
        if (preg_match(self::ORDERING_REGEX, $ordering) === 0) {
            throw new InvalidArgumentException('Invalid ordering string: ' . $ordering);
        }

        $relationship = new Relationship();

        for ($i = 0; $i < 3; ++$i) {
            $char = substr($ordering, $i, 1);
            $target = $parts[$i + 1];

            if ($char === 'p') {
                $relationship->setPredicate($target);
                continue;
            }

            $targetParts = explode(self::ENTITY_KEY_DELIMITER, $target);

            if (count($targetParts) !== 2) {
                throw new InvalidArgumentException('Malformed reference in hex key: ' . $target);
            }

            $connectable = (new Connectable())
                ->setName($targetParts[0])
                ->setId($targetParts[1]);

            $method = ($char === 's') ? 'setSubject' : 'setObject';
            $relationship->$method($connectable);
        }

        return $relationship;
    }
}
