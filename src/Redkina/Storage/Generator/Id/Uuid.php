<?php

namespace DevDeclan\Redkina\Storage\Generator\Id;

use DevDeclan\Redkina\Storage\Generator\IdInterface;
use Ramsey\Uuid\Uuid as U;

/**
 * ID Generator which returns a UUID
 *
 * @package DevDeclan\Redkina\IdGenerator
 */
class Uuid implements IdInterface
{
    /**
     * @return string
     * @throws \Exception
     */
    public function generate(): string
    {
        return U::uuid4()->toString();
    }
}
