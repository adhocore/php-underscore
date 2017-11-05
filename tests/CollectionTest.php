<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\Underscore as _;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_array_json_props()
    {
        $_ = _::_([9, 'a' => 'Apple', 5, 8, 'c' => 'Cat']);

        $this->assertSame('Apple', $_['a']);
        $this->assertSame(8, $_[2]);
        $this->assertTrue(isset($_['c']));
        $this->assertFalse(isset($_['D']));
        $this->assertCount(5, $_);

        unset($_['c']);

        $this->assertSame(4, count($_));

        $_['d'] = 'Dog'; // Set new
        $_[0]   = 8;     // Override

        $this->assertCount(5, $_);

        $json = json_encode($data = [8, 'a' => 'Apple', 5, 8, 'd' => 'Dog']);
        $this->assertSame($json, json_encode($_));
        $this->assertSame($json, (string) $_);

        foreach ($_ as $key => $value) {
            $this->assertSame($data[$key], $value);
        }
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Undefined offset: 5
     */
    public function test_get()
    {
        $_ = _::_([1, 5, 9]);

        $this->assertSame([1, 5, 9], $_->get(), 'get all');
        $this->assertSame(5, $_->get(1), 'get by key');

        $this->assertSame(null, $_->get(5), 'get non existing key');
    }

    public function test_each()
    {
        $answers = [];
        _::_([1, 2, 3])->each(function ($num) use (&$answers) {
            $answers[] = $num * 5;
        });

        $this->assertSame([5, 10, 15], $answers, 'callback applied on each member');
        $this->assertCount(3, $answers, 'callback applied exactly 3 times');

        $answers = [];
        _::_(['one' => 1, 'two' => 2, 'three' => 3])->each(function ($num, $index) use (&$answers) {
            $answers[] = $index;
        });

        $this->assertSame(['one', 'two', 'three'], $answers, 'callback applied on each member of assoc array');
    }

    public function test_map_collect()
    {
        $mapped = _::_([1, 2, 3])->map(function ($num) {
            return $num * 2;
        });

        $this->assertSame([2, 4, 6], $mapped->get(), 'callback applied on each member');

        $mapped = _::_([['a' => 1], ['a' => 2]])->collect(function ($row) {
            return $row['a'];
        });

        $this->assertSame([1, 2], $mapped->get(), 'map prop');
    }

    public function test_reduce_foldl_inject()
    {
        $sum = _::_([1, 2, 3])->reduce(function ($sum, $num) {
            return $num + $sum;
        }, 0);

        $this->assertSame(6, $sum, 'sum by reduce');

        $sum = _::_([1, 2, 3])->foldl(function ($sum, $num) {
            return $num + $sum;
        }, 10);

        $this->assertSame(10 + 6, $sum, 'sum by reduce with initial 10');

        $prod = _::_([1, 2, 3, 4])->inject(function ($prod, $num) {
            return $prod * $num;
        }, 1);

        $this->assertSame(24, $prod, 'prod by reduce with initial 1');

        $concat = _::_([1, 2, 3, 4])->inject(function ($concat, $num) {
            return $concat . $num;
        }, '');

        $this->assertSame('1234', $concat, 'concat by reduce');
    }

    public function test_reduceRight_foldr()
    {
        $sum = _::_([1, 2, 3])->reduce(function ($sum, $num) {
            return $num + $sum;
        }, 0);

        $this->assertSame(6, $sum, 'sum by reduceRight');

        $concat = _::_([1, 2, 3, 4])->foldr(function ($concat, $num) {
            return $concat . $num;
        }, '');

        $this->assertSame('4321', $concat, 'concat by reduceRight');
    }

    public function test_find_detect()
    {
        $num = _::_([1, 2, 4, 3])->find(function ($num) {
            return $num > 2;
        });

        $this->assertSame(4, $num, 'first num gt 2');

        $num = _::_([1, 2, 3])->detect(function ($num) {
            return $num > 4;
        });

        $this->assertNull($num, 'first num gt 5 doesnt exist');
    }

    public function test_filter_select()
    {
        $gt2 = _::_([1, 2, 4, 0, 3])->filter(function ($num) {
            return $num > 2;
        });

        $this->assertSame([4, 3], array_values($gt2->get()), 'nums gt 2');

        $odds = _::_([1, 2, 3, 4, 5, 7, 6])->select(function ($num) {
            return $num % 2 === 1;
        });

        $this->assertSame([1, 3, 5, 7], array_values($odds->get()), 'odd nums');
    }

    public function test_reject()
    {
        $evens = _::_([1, 2, 3, 4, 5, 7, 6])->reject(function ($num) {
            return $num % 2 !== 0;
        });

        $this->assertSame([2, 4, 6], array_values($evens->get()), 'even nums');
    }

    public function test_every_all()
    {
        $gt0 = _::_([1, 2, 3, 4])->every(function ($num) {
            return $num > 0;
        });

        $this->assertTrue($gt0, 'every nums gt 0');

        $lt0 = _::_([1, 2, 3, 4])->all(function ($num) {
            return $num < 0;
        });

        $this->assertFalse($lt0, 'every nums lt 0');
    }

    public function test_some_any()
    {
        $pos = _::_([1, 2, 0, 4, -1])->some(function ($num) {
            return $num > 0;
        });

        $this->assertTrue($pos, 'some positive numbers');

        $neg = _::_([1, 2, 4])->any(function ($num) {
            return $num < 0;
        });

        $this->assertFalse($neg, 'no any neg num');
    }

    public function test_contains_includes()
    {
        $contains = _::_([1, 2, 4])->contains(2);

        $this->assertTrue($contains, 'contains 2');

        $includes = _::_([1, 2, 4])->includes(-3);

        $this->assertFalse($includes, 'doesnt include -3');
    }

    public function test_invoke()
    {
        $sum = _::_([1, 2, 4])->invoke(function () {
            return array_sum(func_get_args());
        });

        $this->assertSame(7, $sum, 'sum items by invoke fn');
    }

    public function test_pluck()
    {
        $people = _::_([['name' => 'moe', 'age' => 30], ['name' => 'curly']]);
        $names  = $people->pluck('name')->get();
        $ages   = $people->pluck('age')->get();

        $this->assertSame(['moe', 'curly'], $names, 'pluck names');
        $this->assertSame([30], $ages, 'pluck ages');
    }

    public function test_where()
    {
        $list = _::_([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2], ['a' => 1, 'b' => 3]]);
        $a1   = $list->where(['a' => 1])->get();
        $a1b2 = $list->where(['a' => 1, 'b' => 2])->get();
        $c3   = $list->where(['c' => 3])->get();

        $this->assertSame([['a' => 1, 'b' => 2], 2 => ['a' => 1, 'b' => 3]], $a1, 'where a = 1');
        $this->assertSame([['a' => 1, 'b' => 2]], $a1b2, 'where a = 1 and b = 2');
        $this->assertSame([], $c3, 'where c = 3');
    }

    public function test_findWhere()
    {
        $list = _::_([['a' => 1, 'b' => 2], ['a' => 2, 'b' => 2], ['a' => 1, 'b' => 3]]);
        $b3   = $list->findWhere(['b' => 3]);
        $a2b1 = $list->findWhere(['a' => 2, 'b' => 1]);

        $this->assertSame(['a' => 1, 'b' => 3], $b3, 'findwhere b = 3');
        $this->assertNull($a2b1, 'where a = 2 and b = 1');
    }
}
