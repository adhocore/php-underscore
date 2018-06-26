<?php

namespace Ahc\Underscore;

final class Underscore extends UnderscoreFunction
{
    /**
     * Constructor.
     *
     * @param array|mixed $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    /**
     * A static shortcut to constructor.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public static function _($data = null)
    {
        return new static($data);
    }

    /**
     * Generates a function that always returns a constant value.
     *
     * @param mixed $value
     *
     * @return callable
     */
    public function constant($value)
    {
        return function () use ($value) {
            return $value;
        };
    }

    /**
     * No operation!
     *
     * @return void
     */
    public function noop()
    {
        // ;)
    }

    /**
     * Run callable n times and create new collection.
     *
     * @param int      $n
     * @param callable $fn
     *
     * @return self
     */
    public function times($n, callable $fn)
    {
        $data = [];

        for ($i = 0; $i < $n; $i++) {
            $data[$i] = $fn($i);
        }

        return new static($data);
    }

    /**
     * Return a random integer between min and max (inclusive).
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function random($min, $max)
    {
        return \mt_rand($min, $max);
    }

    /**
     * Generate unique ID (unique for current go/session).
     *
     * @param string $prefix
     *
     * @return string
     */
    public function uniqueId($prefix = '')
    {
        static $id = 0;

        return $prefix . (++$id);
    }
}
