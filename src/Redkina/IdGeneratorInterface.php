<?php

namespace DevDeclan\Redkina;

/**
 * Defines the contract for ID generators
 *
 * @package DevDeclan\Redkina
 */
interface IdGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
