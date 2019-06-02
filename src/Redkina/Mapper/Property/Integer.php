<?php

namespace DevDeclan\Redkina\Mapper\Property;

use DevDeclan\Redkina\MapperInterface;

class Integer implements MapperInterface
{
    /**
     * @param int $input
     * @return string
     */
    public function in($input)
    {
        return (string) $input;
    }

    /**
     * @param string $output
     * @return int
     */
    public function out($output)
    {
        return intval($output);
    }
}
