<?php

namespace DevDeclan\Redkina;

class Bond
{
    const DEFAULT_TYPE = 'is_bonded_with';

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
     * @var RegistryInterface
     */
    protected $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function create(BondableInterface $subject, BondableInterface $object, string $predicate = self::DEFAULT_TYPE)
    {
        $mapped = $this->mapArguments($subject, $object, $predicate);

        return array_reduce($this->getCombinations(), function ($acc, $combination) use ($mapped) {
            $results = $acc ?? [];

            $results[] = $this->generateKey($combination, $mapped);

            return $results;
        });
    }

    public function getCombinations(): array
    {
        return $this->combinations;
    }

    protected function mapArguments(BondableInterface $subject, BondableInterface $object, string $predicate)
    {
        return [
            self::SUBJECT => $subject,
            self::OBJECT => $object,
            self::PREDICATE => $predicate
        ];
    }

    protected function generateKey(array $combination, array $mappedArgs)
    {
        $parts = [vsprintf('%s%s%s', $combination)];

        foreach ($combination as $partType) {
            if (is_string($mappedArgs[$partType])) {
                $parts[] = $mappedArgs[$partType];
                continue;
            }

            $parts[] = sprintf(
                '%s.%s',
                $this->registry->getType(get_class($mappedArgs[$partType])),
                $mappedArgs[$partType]->getId()
            );
        }

        return vsprintf('%s:%s:%s:%s', $parts);
    }
}
