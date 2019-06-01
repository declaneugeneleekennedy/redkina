<?php

namespace Declaneugeneleekennedy\Redkina\IdGenerator;

use Ramsey\Uuid\Uuid as U;

/**
 * ID Generator which returns a UUID
 *
 * @package Declaneugeneleekennedy\Redkina\IdGenerator
 */
class Uuid
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
