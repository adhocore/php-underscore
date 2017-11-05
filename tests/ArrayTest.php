<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\UnderscoreArray as _;

class ArrayTest extends \PHPUnit_Framework_TestCase
{
    public function test_first_last()
    {
        $array = range(rand(5, 10), rand(15, 20));

        $this->assertSame($array[0], _::_($array)->first(), 'first');
        $this->assertSame(array_reverse($array)[0], _::_($array)->last(), 'last');

        $array = ['x' => ['first'], 'z' => 'last'];

        $this->assertSame($array['x'], _::_($array)->first(), 'first');
        $this->assertSame($array['z'], _::_($array)->last(), 'last');
    }

    public function test_compact()
    {
        $array = [0, 'a', '', [], 2, [1]];

        $this->assertSame([1 => 'a', 4 => 2, 5 => [1]], _::_($array)->compact()->get(), 'first');
    }

    public function test_flatten()
    {
        $array = [0, 'a', '', [[1, [2]]], 'b', [[[3]], 4, 'c', new _([5, 'd'])]];

        $this->assertSame(
            [0, 'a', '', 1, 2, 'b', 3, 4, 'c', 5, 'd'],
            _::_($array)->flatten()->get(),
            'flatten'
        );
    }

    public function test_unique_uniq()
    {
        $array = [0, 'a', '', 1, '', 0, 2, 'a', 3, 1];

        $this->assertSame(
            [0, 'a', '', 1, 6 => 2, 8 => 3],
            _::_($array)->unique()->get(),
            'unique'
        );

        $array = ['a', '', 'a', 1, '', 0, 1, 'b', 3, 2];

        $this->assertSame(
            ['a', '', 3 => 1, 5 => 0, 7 => 'b', 8 => 3, 9 => 2],
            _::_($array)->uniq(function ($i) {
                return $i;
            })->get(),
            'uniq'
        );
    }
}
