<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\Helper;
use Ahc\Underscore\Underscore as _;

class Stub
{
    public function toArray()
    {
        return ['a', 'b', 'c'];
    }
}

class Json implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return ['a' => 1, 'b' => 2, 'c' => 3];
    }
}

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function test_asArray()
    {
        $this->assertSame(['one'], Helper::asArray('one'));
        $this->assertSame([1, 2], Helper::asArray([1, 2]));
        $this->assertSame(['a', 'b', 'c'], Helper::asArray(new Stub));
        $this->assertSame(['a', 1, 'c', 3], Helper::asArray(new _(['a', 1, 'c', 3])));
        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 3], Helper::asArray(new Json));
    }
}
