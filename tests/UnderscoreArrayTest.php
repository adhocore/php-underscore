<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\UnderscoreArray as _;

class UnderscoreArrayTest extends \PHPUnit_Framework_TestCase
{
    public function test_first_last()
    {
        $array = range(rand(5, 10), rand(15, 20));

        $this->assertSame($array[0], _::_($array)->first(), 'first');
        $this->assertSame(array_reverse($array)[0], _::_($array)->last(), 'last');

        $array = ['x' => ['first'], 'z' => 'last'];

        $this->assertSame($array['x'], _::_($array)->head(), 'first');
        $this->assertSame($array['z'], _::_($array)->tail(), 'last');

        $array = range(1, 5);

        $this->assertSame([1, 2, 3], _::_($array)->take(3), 'first 3');
        $this->assertSame([1, 2, 3, 4, 5], _::_($array)->first(6), 'first 6 (n + 1)');
        $this->assertSame([2 => 3, 3 => 4, 4 => 5], _::_($array)->drop(3), 'last 3');
        $this->assertSame([1, 2, 3, 4, 5], _::_($array)->last(6), 'last 6 (n + 1)');
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

    public function test_difference_without()
    {
        $array = [1, 2, 1, 'a' => 3, 'b' => [4]];

        $this->assertSame(
            [1 => 2, 'a' => 3],
            _::_($array)->difference([1, [4]])->get(),
            'difference'
        );

       $this->assertSame(
            ['a' => 3, 'b' => [4]],
            _::_($array)->without([1, 2])->get(),
            'without'
        );
    }

    public function test_union()
    {
        $array = [1, 2, 'a' => 3];

        $this->assertSame(
            [1, 2, 'a' => 4, 3, 'b' => [5]],
            _::_($array)->union([3, 'a' => 4, 'b' => [5]])->get(),
            'union'
        );
    }

    public function test_intersection()
    {
        $array = [1, 2, 'a' => 3];

        $this->assertSame(
            [1 => 2, 'a' => 3],
            _::_($array)->intersection([2, 'a' => 3, 3])->get(),
            'intersection'
        );
    }

    public function test_zip()
    {
        $array = [1, 2, 'a' => 3, 'b' => 'B'];

        $this->assertSame(
            [[1, 2], [2, 4], 'a' => [3, 5], 'b' => ['B', null]],
            _::_($array)->zip([2, 4, 'a' => 5])->get(),
            'zip'
        );
    }

    public function test_object()
    {
        $array = [[1, 2], 'a' => 3, 'b' => 'B'];

        foreach (_::_($array)->object() as $index => $value) {
            $this->assertTrue(is_object($value));
            $this->assertSame($index, $value->index);
            $this->assertSame($array[$index], $value->value);
        }
    }

    public function test_firstIndex_lastIndex()
    {
        $array = _::_([[1, 2], 'a' => 3, 'x' => 4, 'y' => 2, 'b' => 'B']);

        $this->assertSame(0, $array->firstIndex());
        $this->assertSame('b', $array->lastIndex());

        $this->assertSame('x', $array->firstIndex(function ($i) {
            return is_numeric($i) && $i % 2 === 0;
        }));
        $this->assertSame('y', $array->lastIndex(function ($i) {
            return is_numeric($i) && $i % 2 === 0;
        }));
    }

    public function test_indexOf_lastIndexOf()
    {
        $array = _::_([[1, 2], 'a' => 2, 'x' => 4, 'y' => 2, 'b' => 'B']);

        $this->assertSame('a', $array->indexOf(2));
        $this->assertSame('y', $array->lastIndexOf(2));
    }

    public function test_range()
    {
        $this->assertSame([4, 5, 6, 7, 8, 9], _::_()->range(4, 9)->get());
        $this->assertSame([10, 12, 14, 16, 18], _::_()->range(10, 18, 2)->get());
        $this->assertSame([20, 19, 18, 17, 16], _::_()->range(20, 16, -1)->get());
    }
}
