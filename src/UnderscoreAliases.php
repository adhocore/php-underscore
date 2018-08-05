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

trait UnderscoreAliases
{
    /**
     * Alias of first().
     *
     * @param int $n
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    public function head($n = 1)
    {
        return $this->first($n);
    }

    /**
     * Alias of first().
     *
     * @param int $n
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    public function take($n = 1)
    {
        return $this->first($n);
    }

    /**
     * Alias of last().
     *
     * @param int $n
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    public function tail($n = 1)
    {
        return $this->last($n);
    }

    /**
     * Alias of last().
     *
     * @param int $n
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    public function drop($n = 1)
    {
        return $this->last($n);
    }

    /**
     * Alias of unique().
     *
     * @param callable|string $fn The callback. String is resolved to value of that index.
     *
     * @return self
     */
    public function uniq($fn = null)
    {
        return $this->unique($fn);
    }

    /**
     * Alias of difference().
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function without($data)
    {
        return $this->difference($data);
    }

    /**
     * Alias of map().
     *
     * @param callable $fn The callback.
     *
     * @return self
     */
    public function collect(callable $fn)
    {
        return $this->map($fn);
    }

    /**
     * Alias of reduce().
     *
     * @param callable $fn   The callback.
     * @param mixed    $memo The initial value carried over to each iteration and returned finally.
     *
     * @return mixed
     */
    public function foldl(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    /**
     * Alias of reduce().
     *
     * @param callable $fn   The callback.
     * @param mixed    $memo The initial value carried over to each iteration and returned finally.
     *
     * @return mixed
     */
    public function inject(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    /**
     * Alias of reduceRight().
     *
     * @param callable $fn   The callback.
     * @param mixed    $memo The initial value carried over to each iteration and returned finally.
     *
     * @return mixed
     */
    public function foldr(callable $fn, $memo)
    {
        return $this->reduceRight($fn, $memo);
    }

    /**
     * Alias of find().
     *
     * @param callable $fn       The truth test callback.
     * @param bool     $useValue Whether to return value or the index on match.
     *
     * @return mixed|null
     */
    public function detect(callable $fn)
    {
        return $this->find($fn);
    }

    /**
     * Alias of filter().
     *
     * @param callable|string|null $fn The truth test callback.
     *
     * @return self
     */
    public function select(callable $fn = null)
    {
        return $this->filter($fn);
    }

    /**
     * Alias of every().
     *
     * @param callable $fn The truth test callback.
     *
     * @return bool
     */
    public function all(callable $fn)
    {
        return $this->every($fn);
    }

    /**
     * Alias of some().
     *
     * @param callable $fn The truth test callback.
     *
     * @return bool
     */
    public function any(callable $fn)
    {
        return $this->some($fn);
    }

    /**
     * Alias of contains().
     */
    public function includes($item)
    {
        return $this->contains($item);
    }

    /**
     * Alias of count().
     *
     * @return int
     */
    public function size()
    {
        return $this->count();
    }
}
