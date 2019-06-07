<?php

namespace DevDeclan\Redkina\Generator\Id;

use DevDeclan\Redkina\Generator\IdInterface;
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
