<?php

namespace DevDeclan\Redkina\Mapper\Property;

use DevDeclan\Redkina\MapperInterface;

class Generic implements MapperInterface
{
    /**
     * @param mixed $input
     * @return string
     */
    public function in($input)
    {
        return (string) $input;
    }

    /**
     * @param string $output
     * @return string
     */
    public function out($output)
    {
        return (string) $output;
    }
}
