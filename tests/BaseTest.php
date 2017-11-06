<?php

namespace Ahc\Underscore\Tests;

use Ahc\Underscore\UnderscoreBase as _;

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

class BaseTest extends \PHPUnit_Framework_TestCase
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
        $this->assertTrue(is_float(_::_()->now()));
    }
}
