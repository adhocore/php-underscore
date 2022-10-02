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

class Stub
{
    public function toArray()
    {
        return ['a', 'b', 'c'];
    }
}

class Json implements \JsonSerializable
{
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return ['a' => 1, 'b' => 2, 'c' => 3];
    }
}

class Hom
{
    public $n;
    public $square;

    public function __construct($n)
    {
        $this->n      = $n;
        $this->square = $n * $n;
    }

    public function even()
    {
        return $this->n % 2 === 0;
    }
}

class UnderscoreBaseTest extends TestCase
{
    public function test_asArray()
    {
        $this->assertSame(['one'], (new _)->asArray('one'));
        $this->assertSame([1, 2], (new _)->asArray([1, 2]));
        $this->assertSame(['a', 'b', 'c'], (new _)->asArray(new Stub));
        $this->assertSame(['a', 1, 'c', 3], (new _)->asArray(new _(['a', 1, 'c', 3])));
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], (new _)->asArray(new Json));
    }

    public function test_alias()
    {
        $this->assertTrue(class_exists('Ahc\Underscore'));

        $this->assertEquals(new \Ahc\Underscore\Underscore, new \Ahc\Underscore);
    }

    public function test_underscore()
    {
        $this->assertTrue(function_exists('underscore'));

        $this->assertInstanceOf(_::class, underscore());
    }

    public function test_now()
    {
        $this->assertNotEmpty(_::_()->now());
    }

    public function test_keys_values()
    {
        $array = [[1, 2], 'a' => 3, 7, 'b' => 'B'];

        $this->assertSame(array_keys($array), underscore($array)->keys()->get());
        $this->assertSame(array_values($array), underscore($array)->values()->get());
    }

    public function test_pairs()
    {
        $array = ['a' => 3, 7, 'b' => 'B'];

        $this->assertSame(['a' => ['a', 3], 0 => [0, 7], 'b' => ['b', 'B']], underscore($array)->pairs()->get());
    }

    public function test_invert()
    {
        $array = ['a' => 3, 7, 'b' => 'B'];

        $this->assertSame(array_flip($array), underscore($array)->invert()->get());
    }

    public function test_pick_omit()
    {
        $array = underscore(['a' => 3, 7, 'b' => 'B', 1 => ['c', 5]]);

        $this->assertSame([7, 'b' => 'B'], $array->pick([0, 'b'])->get());
        $this->assertSame(['b' => 'B', 1 => ['c', 5]], $array->pick(1, 'b')->get());
        $this->assertSame(['a' => 3, 7], $array->omit([1, 'b'])->get());
        $this->assertSame(['b' => 'B', 1 => ['c', 5]], $array->omit('a', 0)->get());
    }

    public function test_clone_tap()
    {
        $main = underscore(['will', 'be', 'cloned']);
        $clon = $main->clon();

        $this->assertNotSame($main, $clon, 'hard equal');
        $this->assertNotSame(spl_object_hash($main), spl_object_hash($clon));
        $this->assertEquals($main, $clon, 'soft equal');
        $this->assertSame($main->toArray(), $clon->toArray());

        $tap = $main->tap(function ($und) {
            return $und->values();
        });

        $this->assertSame($main, $tap, 'hard equal');
    }

    public function test_mixin()
    {
        _::mixin('double', function () {
            return $this->map(function ($v) {
                return $v * 2;
            });
        });

        $und = underscore([10, 20, 30]);

        $this->assertIsCallable([$und, 'double']);
        $this->assertSame([20, 40, 60], $und->double()->toArray());

        $this->expectException(\Ahc\Underscore\UnderscoreException::class);
        $this->expectExceptionMessage("The mixin with name 'notMixedIn' is not defined");

        $und->notMixedIn();
    }

    public function test_valueOf()
    {
        $this->assertSame('[]', underscore()->valueOf());
        $this->assertSame('[1,2]', underscore([1, 2])->valueOf());
        $this->assertSame('["a","b"]', underscore(['a', 'b'])->valueOf());
    }

    public function test_hom()
    {
        $i = 10;

        while ($i--) {
            $u[] = new Hom($i);
        }

        $u  = underscore($u);
        $sq = $u->filter->even()->map->square->get();

        // keys are kept intact :)
        $this->assertSame([1 => 64, 3 => 36, 5 => 16, 7 => 4, 9 => 0], $sq);
    }

    public function test_hom_throws()
    {
        $this->expectException(\Ahc\Underscore\UnderscoreException::class);
        $this->expectExceptionMessage("The 'anon' is not defined");

        underscore()->anon->value();
    }
}
