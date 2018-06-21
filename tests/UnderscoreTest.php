<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\Underscore as _;

class UnderscoreTest extends \PHPUnit_Framework_TestCase
{
    public function test_constant()
    {
        foreach ([1, 'A', [], new \stdClass()] as $value) {
            $fn = _::_()->constant($value);

            $this->assertSame($value, $fn());
        }
    }

    public function test_noop()
    {
        $epsilon = 0.0000000001;

        $t = microtime(1);
        $m = memory_get_usage();
        $x = _::_()->noop();
        $t = microtime(1) - $t;
        $m = memory_get_usage() - $m;

        $this->assertLessThanOrEqual($t, $epsilon);
        $this->assertLessThanOrEqual($m, $epsilon);
    }

    public function test_times()
    {
        $fn = function ($i) {
            return $i * 2;
        };

        $o = _::_()->times(5, $fn);

        $this->assertSame([0, 2, 4, 6, 8], $o->toArray());
    }

    public function test_random()
    {
        $i = 10;

        while ($i--) {
            $cases[rand(1, 10)] = rand(11, 20);
        }

        foreach ($cases as $l => $r) {
            $rand = _::_()->random($l, $r);

            $this->assertGreaterThanOrEqual($l, $rand);
            $this->assertLessThanOrEqual($r, $rand);
        }
    }

    public function test_unique_id()
    {
        $u  = _::_()->uniqueId();
        $u1 = _::_()->uniqueId();
        $u3 = _::_()->uniqueId('id:');

        $this->assertSame('1', $u);
        $this->assertSame('2', $u1);
        $this->assertSame('id:3', $u3);
    }
}
