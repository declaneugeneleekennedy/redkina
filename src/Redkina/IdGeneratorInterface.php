<?php

namespace Declaneugeneleekennedy\Redkina;

/**
 * Defines the contract for ID generators
 *
 * @package Declaneugeneleekennedy\Redkina
 */
interface IdGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
