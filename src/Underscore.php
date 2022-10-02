<?php

/*
 * This file is part of the PHP-UNDERSCORE package.
 *
 * (c) Jitendra Adhikari <jiten.adhikary@gmail.com>
 *     <https://github.com/adhocore>
 *
 * Licensed under MIT license.
 */

namespace Ahc\Underscore;

final class Underscore extends UnderscoreFunction
{
    /**
     * Constructor.
     *
     * @param array|mixed $data
     */
    public function __construct(mixed $data = [])
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
    public static function _($data = null): self
    {
        return new static($data);
    }

    /**
     * Generates a function that always returns a constant value.
     */
    public function constant(mixed $value): callable
    {
        return fn () => $value;
    }

    /**
     * No operation!
     */
    public function noop(): void
    {
        // ;)
    }

    /**
     * Run callable n times and create new collection.
     */
    public function times(int $n, callable $fn): self
    {
        $data = [];

        for ($i = 0; $i < $n; $i++) {
            $data[$i] = $fn($i);
        }

        return new static($data);
    }

    /**
     * Return a random integer between min and max (inclusive).
     */
    public function random(int $min, int $max): int
    {
        return \mt_rand($min, $max);
    }

    /**
     * Generate unique ID (unique for current go/session).
     */
    public function uniqueId(string $prefix = ''): string
    {
        static $id = 0;

        return $prefix . (++$id);
    }
}
