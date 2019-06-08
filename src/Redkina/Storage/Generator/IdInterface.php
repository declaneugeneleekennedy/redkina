<?php

namespace DevDeclan\Redkina\Storage\Generator;

/**
 * Defines the contract for ID generators
 *
 * @package DevDeclan\Redkina
 */
interface IdInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}
