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

class UnderscoreCollection extends UnderscoreBase
{
    /**
     * Apply given callback to each of the items in collection.
     */
    public function each(callable $fn): self
    {
        foreach ($this->data as $index => $value) {
            $fn($value, $index);
        }

        return $this;
    }

    /**
     * Update the value of each items with the result of given callback.
     */
    public function map(callable $fn): self
    {
        $data = [];

        foreach ($this->data as $index => $value) {
            $data[$index] = $fn($value, $index);
        }

        return new static($data);
    }

    /**
     * Iteratively reduce the array to a single value using a callback function.
     *
     * @param callable $fn   The callback.
     * @param mixed    $memo The initial value carried over to each iteration and returned finally.
     *
     * @return mixed
     */
    public function reduce(callable $fn, $memo)
    {
        return \array_reduce($this->data, $fn, $memo);
    }

    /**
     * Same as reduce but applies the callback from right most item first.
     *
     * @param callable $fn   The callback.
     * @param mixed    $memo The initial value carried over to each iteration and returned finally.
     *
     * @return mixed
     */
    public function reduceRight(callable $fn, mixed $memo): mixed
    {
        return \array_reduce(\array_reverse($this->data, true), $fn, $memo);
    }

    /**
     * Find the first item (or index) that passes given truth test.
     *
     * @param callable $fn       The truth test callback.
     * @param bool     $useValue Whether to return value or the index on match.
     *
     * @return mixed|null
     */
    public function find(callable $fn, bool $useValue = true): mixed
    {
        foreach ($this->data as $index => $value) {
            if ($fn($value, $index)) {
                return $useValue ? $value : $index;
            }
        }

        return null;
    }

    /**
     * Find and return all the items that passes given truth test.
     *
     * @param callable|string|null $fn The truth test callback.
     *
     * @return self
     */
    public function filter(callable $fn = null): self
    {
        if (null === $fn) {
            return new static(\array_filter($this->data));
        }

        $data = \array_filter($this->data, $fn, \ARRAY_FILTER_USE_BOTH);

        return new static($data);
    }

    /**
     * Find and return all the items that fails given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return self
     */
    public function reject(callable $fn): self
    {
        $data = \array_filter($this->data, $this->negate($fn), \ARRAY_FILTER_USE_BOTH);

        return new static($data);
    }

    /**
     * Tests if all the items pass given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return bool
     */
    public function every(callable $fn): bool
    {
        return $this->match($fn, true);
    }

    /**
     * Tests if some (at least one) of the items pass given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return bool
     */
    public function some(callable $fn): bool
    {
        return $this->match($fn, false);
    }

    /**
     * Check if the items match with given truth test.
     *
     * @internal Used by every() and some().
     *
     * @param callable $fn  The truth test callback.
     * @param bool     $all All or one?
     *
     * @return bool
     */
    protected function match(callable $fn, bool $all = true): bool
    {
        foreach ($this->data as $index => $value) {
            if ($all ^ $fn($value, $index)) {
                return !$all;
            }
        }

        return $all;
    }

    /**
     * Check if the collection contains given item.
     */
    public function contains(mixed $item): bool
    {
        return \in_array($item, $this->data);
    }

    /**
     * Invoke a callback using all of the items as arguments.
     *
     * @param callable $fn The callback.
     *
     * @return mixed Whatever the callback yields.
     */
    public function invoke(callable $fn): mixed
    {
        return $fn(...$this->data);
    }

    /**
     * Pluck given property from each of the items.
     *
     * @param string|int $columnKey
     * @param string|int $indexKey
     *
     * @return self
     */
    public function pluck(mixed $columnKey, mixed $indexKey = null): self
    {
        $data = \array_column($this->data, $columnKey, $indexKey);

        return new static($data);
    }

    /**
     * Filter only the items that contain all the given props (matching both index and value).
     */
    public function where(array $props): self
    {
        return $this->filter($this->matcher($props));
    }

    /**
     * Get the first item that contains all the given props (matching both index and value).
     */
    public function findWhere(array $props): mixed
    {
        return $this->find($this->matcher($props));
    }

    /**
     * Gives props matcher callback used by where() and findWhere().
     *
     * @internal
     *
     * @param array $props Key value pairs.
     *
     * @return callable
     */
    protected function matcher(array $props): callable
    {
        return function ($value, $index) use ($props) {
            foreach ($props as $prop => $criteria) {
                if (\array_column([$value], $prop) != [$criteria]) {
                    return false;
                }
            }

            return true;
        };
    }

    /**
     * Find the maximum value using given callback or just items.
     *
     * @param callable|string|null $fn The callback. String is resolved to value of that index.
     *
     * @return mixed
     */
    public function max($fn = null): mixed
    {
        return $this->maxMin($fn, true);
    }

    /**
     * Find the minimum value using given callback or just items.
     *
     * @param callable|string|null $fn The callback. String is resolved to value of that index.
     *
     * @return mixed
     */
    public function min($fn = null): mixed
    {
        return $this->maxMin($fn, false);
    }

    /**
     * The max/min value retriever used by max() and min().
     *
     * @internal
     *
     * @param callable|string|null $fn    The reducer callback.
     * @param bool                 $isMax
     *
     * @return mixed
     */
    protected function maxMin($fn = null, bool $isMax = true): mixed
    {
        $fn = $this->valueFn($fn);

        return $this->reduce(function ($carry, $value) use ($fn, $isMax) {
            $value = $fn($value);

            if (!\is_numeric($value)) {
                return $carry;
            }

            return null === $carry
                ? $value
                : ($isMax ? \max($carry, $value) : \min($carry, $value));
        }, null);
    }

    /**
     * Randomize the items keeping the indexes intact.
     *
     * @return self
     */
    public function shuffle(): self
    {
        $data = [];
        $keys = \array_keys($this->data);

        shuffle($keys);

        foreach ($keys as $index) {
            $data[$index] = $this->data[$index];
        }

        return new static($data);
    }

    /**
     * Get upto n items in random order.
     *
     * @param int $n Number of items to include.
     *
     * @return self
     */
    public function sample(int $n = 1): self
    {
        $shuffled = $this->shuffle()->get();

        return new static(\array_slice($shuffled, 0, $n, true));
    }

    /**
     * Sort items by given callback and maintain indexes.
     *
     * @param callable $fn The callback. Use null to sort based only on values.
     *
     * @return self
     */
    public function sortBy($fn): self
    {
        $data = $this->map($this->valueFn($fn))->get();

        \asort($data); // Keep keys.

        foreach ($data as $index => $value) {
            $data[$index] = $this->data[$index];
        }

        return new static($data);
    }

    /**
     * Group items by using the result of callback as index. The items in group will have original index intact.
     *
     * @param callable|string $fn The callback. String is resolved to value of that index.
     *
     * @return self
     */
    public function groupBy($fn): self
    {
        return $this->group($fn, true);
    }

    /**
     * Reindex items by using the result of callback as new index.
     *
     * @param callable|string $fn The callback. String is resolved to value of that index.
     *
     * @return self
     */
    public function indexBy($fn): self
    {
        return $this->group($fn, false);
    }

    /**
     * Count items in each group indexed by the result of callback.
     *
     * @param callable|string $fn The callback. String is resolved to value of that index.
     *
     * @return self
     */
    public function countBy($fn): self
    {
        return $this->group($fn, true)->map(fn ($value) => \count($value));
    }

    /**
     * Group/index items by using the result of given callback.
     *
     * @internal
     */
    protected function group($fn, bool $isGroup = true): self
    {
        $data = [];
        $fn   = $this->valueFn($fn);

        foreach ($this->data as $index => $value) {
            $isGroup ? $data[$fn($value, $index)][$index] = $value : $data[$fn($value, $index)] = $value;
        }

        return new static($data);
    }

    /**
     * Separate the items into two groups: one passing given truth test and other failing.
     */
    public function partition(callable $fn): self
    {
        $data = [[/* pass */], [/* fail */]];
        $fn   = $this->valueFn($fn);

        $this->each(static function ($value, $index) use ($fn, &$data) {
            $data[$fn($value, $index) ? 0 : 1][] = $value;
        });

        return new static($data);
    }
}
