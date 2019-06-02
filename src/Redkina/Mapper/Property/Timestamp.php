<?php

namespace DevDeclan\Redkina\Mapper\Property;

use DevDeclan\Redkina\MapperInterface;
use DateTime;

class Timestamp implements MapperInterface
{
    /**
     * @param DateTime $input
     * @return string
     */
    public function in($input)
    {
        return $input->format('c');
    }

    /**
     * @param string $output
     * @return DateTime|null
     */
    public function out($output)
    {
        return date_create($output) ?: null;
    }
}
