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
     */
    public function head(int $n = 1)
    {
        return $this->first($n);
    }

    /**
     * Alias of first().
     */
    public function take(int $n = 1)
    {
        return $this->first($n);
    }

    /**
     * Alias of last().
     */
    public function tail(int $n = 1)
    {
        return $this->last($n);
    }

    /**
     * Alias of last().
     */
    public function drop(int $n = 1)
    {
        return $this->last($n);
    }

    /**
     * Alias of unique().
     */
    public function uniq($fn = null): self
    {
        return $this->unique($fn);
    }

    /**
     * Alias of difference().
     */
    public function without(mixed $data): self
    {
        return $this->difference($data);
    }

    /**
     * Alias of map().
     */
    public function collect(callable $fn): self
    {
        return $this->map($fn);
    }

    /**
     * Alias of reduce().
     */
    public function foldl(callable $fn, mixed $memo): mixed
    {
        return $this->reduce($fn, $memo);
    }

    /**
     * Alias of reduce().
     */
    public function inject(callable $fn, mixed $memo): mixed
    {
        return $this->reduce($fn, $memo);
    }

    /**
     * Alias of reduceRight().
     */
    public function foldr(callable $fn, mixed $memo): mixed
    {
        return $this->reduceRight($fn, $memo);
    }

    /**
     * Alias of find().
     */
    public function detect(callable $fn): mixed
    {
        return $this->find($fn);
    }

    /**
     * Alias of filter().
     */
    public function select(callable $fn = null): self
    {
        return $this->filter($fn);
    }

    /**
     * Alias of every().
     */
    public function all(callable $fn): bool
    {
        return $this->every($fn);
    }

    /**
     * Alias of some().
     */
    public function any(callable $fn): bool
    {
        return $this->some($fn);
    }

    /**
     * Alias of contains().
     */
    public function includes(mixed $item): bool
    {
        return $this->contains($item);
    }

    /**
     * Alias of count().
     */
    public function size(): int
    {
        return $this->count();
    }
}
