<?php

namespace DevDeclan\Test\Support\Redkina\Entity\Person;

use DevDeclan\Test\Support\Redkina\Entity\Person;
use DevDeclan\Redkina\Annotation as Redkina;

/**
 * @package DevDeclan\Test\Support\Redkina\Entity\Person
 *
 * @Redkina\Entity(
 *     name = "PersonWithNickname"
 * )
 */
class WithNickname extends Person
{
    /**
     * @var string
     *
     * @Redkina\Property\Generic()
     */
    protected $nickname;

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param  string $nickname
     * @return WithNickname
     */
    public function setNickname(string $nickname): WithNickname
    {
        $this->nickname = $nickname;
        return $this;
    }
}
