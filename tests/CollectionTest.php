<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\Underscore as _;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_each()
    {
        _::_([1, 2, 3])->each(function ($num, $i) {
            $this->assertSame($num, $i + 1, 'each iterators provide value and iteration count');
        });

        $answers = [];
        $count   = 0;
        _::_([1, 2, 3])->each(function ($num) use (&$answers, &$count) {
            $answers[] = $num * 5;
            $count++;
        });

        $this->assertSame($answers, [5, 10, 15], 'callback applied on each member');
        $this->assertSame($count, 3, 'callback applied exactly 3 times');

        $answers = [];
        _::_(['one' => 1, 'two' => 2, 'three' => 3])->each(function ($num, $index) use (&$answers) {
            $answers[] = $index;
        });

        $this->assertSame($answers, ['one', 'two', 'three'], 'callback applied on each member of assoc array');
    }
}
