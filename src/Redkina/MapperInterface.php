<?php

namespace DevDeclan\Redkina;

/**
 * @package DevDeclan\Redkina
 *
 * TODO - There's currently a proposal for the introduction of `mixed` as a type-hint in PHP 7.4/8.0, which would make
 *  this interface more effective: https://wiki.php.net/rfc/mixed-typehint
 */
interface MapperInterface
{
    public function in($input);
    public function out($output);
}
