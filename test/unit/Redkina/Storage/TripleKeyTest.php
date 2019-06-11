<?php

namespace DevDeclan\Test\Unit\Redkina\Storage;

use DevDeclan\Redkina\Storage\Triple;
use DevDeclan\Redkina\Storage\TripleKey;
use DevDeclan\Test\Unit\Redkina\TripleTestCase;
use InvalidArgumentException;

class TripleKeyTest extends TripleTestCase
{
    public function formatHappyPathProvider()
    {
        $data = [];
        for ($i = 0; $i < 6; ++$i) {
            $data[] = [$this->orderings[$i], $this->keys[$i]];
        }

        return $data;
    }

    /**
     * @param string $ordering
     * @param string $expect
     * @dataProvider formatHappyPathProvider
     */
    public function testFormatHappyPath(string $ordering, string $expect)
    {
        $hexKey = new TripleKey($this->happyPathTriple);

        $this->assertEquals($expect, $hexKey->format($ordering));
    }

    public function testFormatOrderingTooShort()
    {
        $hexKey = new TripleKey(new Triple());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Ordering string length is less than 3');

        $hexKey->format('sp');
    }

    public function testFormatOrderingFunkyCharacters()
    {
        $hexKey = new TripleKey(new Triple());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid ordering string: pow');

        $hexKey->format('pow');
    }

    public function hydrateHappyPathProvider()
    {
        return array_map(function ($key) {
            return [$key];
        }, $this->keys);
    }

    /**
     * @param string $key
     * @dataProvider hydrateHappyPathProvider
     */
    public function testHydrateHappyPath(string $key)
    {
        $this->assertEquals($this->happyPathTriple, TripleKey::hydrate($key));
    }

    public function testHydrateBadSegmentCount()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Hex key could not be parsed: this is not even close to being correct');

        TripleKey::hydrate('this is not even close to being correct');
    }

    public function testHydrateOrderingFunkyCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid ordering string: pow');

        TripleKey::hydrate('pow:Foo.123:is_admirer_of:Bar.321');
    }

    public function testHydrateWithBadEntityReference()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Malformed reference in hex key: Mangled');

        TripleKey::hydrate('spo:Foo.123:is_admirer_of:Mangled');
    }
}
