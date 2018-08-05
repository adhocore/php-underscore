<?php

/*
 * This file is part of the PHP-UNDERSCORE package.
 *
 * (c) Jitendra Adhikari <jiten.adhikary@gmail.com>
 *     <https://github.com/adhocore>
 *
 * Licensed under MIT license.
 */

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\Underscore as _;
use PHPUnit\Framework\TestCase;

class UnderscoreArrayTest extends TestCase
{
    public function test_first_last()
    {
        $array = range(rand(5, 10), rand(15, 20));

        $this->assertSame($array[0], underscore($array)->first(), 'first');
        $this->assertSame(array_reverse($array)[0], underscore($array)->last(), 'last');

        $array = ['x' => ['first'], 'z' => 'last'];

        $this->assertSame($array['x'], underscore($array)->head(), 'first');
        $this->assertSame($array['z'], underscore($array)->tail(), 'last');

        $array = range(1, 5);

        $this->assertSame([1, 2, 3], underscore($array)->take(3), 'first 3');
        $this->assertSame([1, 2, 3, 4, 5], underscore($array)->first(6), 'first 6 (n + 1)');
        $this->assertSame([2 => 3, 3 => 4, 4 => 5], underscore($array)->drop(3), 'last 3');
        $this->assertSame([1, 2, 3, 4, 5], underscore($array)->last(6), 'last 6 (n + 1)');
    }

    public function test_compact()
    {
        $array = [0, 'a', '', [], 2, [1]];

        $this->assertSame([1 => 'a', 4 => 2, 5 => [1]], underscore($array)->compact()->get(), 'first');
    }

    public function test_flatten()
    {
        $array = [0, 'a', '', [[1, [2]]], 'b', [[[3]], 4, 'c', new _([5, 'd'])]];

        $this->assertSame(
            [0, 'a', '', 1, 2, 'b', 3, 4, 'c', 5, 'd'],
            underscore($array)->flatten()->get(),
            'flatten'
        );
    }

    public function test_unique_uniq()
    {
        $array = [0, 'a', '', 1, '', 0, 2, 'a', 3, 1];

        $this->assertSame(
            [0, 'a', '', 1, 6 => 2, 8 => 3],
            underscore($array)->unique()->get(),
            'unique'
        );

        $array = ['a', '', 'a', 1, '', 0, 1, 'b', 3, 2];

        $this->assertSame(
            ['a', '', 3 => 1, 5 => 0, 7 => 'b', 8 => 3, 9 => 2],
            underscore($array)->uniq(function ($i) {
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
            underscore($array)->difference([1, [4]])->get(),
            'difference'
        );

        $this->assertSame(
            ['a' => 3, 'b' => [4]],
            underscore($array)->without([1, 2])->get(),
            'without'
        );
    }

    public function test_union()
    {
        $array = [1, 2, 'a' => 3];

        $this->assertSame(
            [1, 2, 'a' => 4, 3, 'b' => [5]],
            underscore($array)->union([3, 'a' => 4, 'b' => [5]])->get(),
            'union'
        );
    }

    public function test_intersection()
    {
        $array = [1, 2, 'a' => 3];

        $this->assertSame(
            [1 => 2, 'a' => 3],
            underscore($array)->intersection([2, 'a' => 3, 3])->get(),
            'intersection'
        );
    }

    public function test_zip()
    {
        $array = [1, 2, 'a' => 3, 'b' => 'B'];

        $this->assertSame(
            [[1, 2], [2, 4], 'a' => [3, 5], 'b' => ['B', null]],
            underscore($array)->zip([2, 4, 'a' => 5])->get(),
            'zip'
        );
    }

    public function test_object()
    {
        $array = [[1, 2], 'a' => 3, 'b' => 'B'];

        foreach (underscore($array)->object() as $index => $value) {
            $this->assertInternalType('object', $value);
            $this->assertSame($index, $value->index);
            $this->assertSame($array[$index], $value->value);
        }
    }

    public function test_findIndex_findLastIndex()
    {
        $array = underscore([[1, 2], 'a' => 3, 'x' => 4, 'y' => 2, 'b' => 'B']);

        $this->assertSame(0, $array->findIndex());
        $this->assertSame('b', $array->findLastIndex());

        $this->assertSame('x', $array->findIndex(function ($i) {
            return is_numeric($i) && $i % 2 === 0;
        }));
        $this->assertSame('y', $array->findLastIndex(function ($i) {
            return is_numeric($i) && $i % 2 === 0;
        }));
    }

    public function test_indexOf_lastIndexOf()
    {
        $array = underscore([[1, 2], 'a' => 2, 'x' => 4, 'y' => 2, 'b' => 'B']);

        $this->assertSame('a', $array->indexOf(2));
        $this->assertSame('y', $array->lastIndexOf(2));
    }

    public function test_range()
    {
        $this->assertSame([4, 5, 6, 7, 8, 9], underscore()->range(4, 9)->get());
        $this->assertSame([10, 12, 14, 16, 18], underscore()->range(10, 18, 2)->get());
        $this->assertSame([20, 19, 18, 17, 16], underscore()->range(20, 16, -1)->get());
    }

    public function test_sortedIndex()
    {
        $nums = [1, 3, 5, 8, 11];
        $new  = 9;

        $newIdx = underscore($nums)->sortedIndex($new, null);

        $this->assertSame(4, $newIdx);

        $data = [
            'a' => ['x' => 1, 'y' => 2],
            'b' => ['x' => 2, 'y' => 2],
            'c' => ['x' => 3, 'y' => 3],
            'd' => ['x' => 4, 'y' => 3],
            'e' => ['x' => 5, 'y' => 4],
        ];

        $new    = ['x' => 3, 'y' => 2];
        $newIdx = underscore($data)->sortedIndex($new, function ($row) {
            return $row['x'] + $row['y'];
        });

        $this->assertSame('c', $newIdx);
    }
}
