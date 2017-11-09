<?php

namespace Ahc\Underscore;

class UnderscoreCollection extends UnderscoreBase
{
    /**
     * Apply given callback to each of the items in collection.
     *
     * @param callable $fn The callback.
     *
     * @return self
     */
    public function each(callable $fn)
    {
        foreach ($this->data as $index => $value) {
            $fn($value, $index);
        }

        return $this;
    }

    /**
     * Update the value of each items with the result of given callback.
     *
     * @param callable $fn The callback.
     *
     * @return self
     */
    public function map(callable $fn)
    {
        $data = [];

        foreach ($this->data as $index => $value) {
            $data[$index] = $fn($value, $index);
        }

        return new static($data);
    }

    /**
     * Alias of map().
     */
    public function collect(callable $fn)
    {
        return $this->map($fn);
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
     * Alias of reduce().
     */
    public function foldl(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    /**
     * Alias of reduce().
     */
    public function inject(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    /**
     * Same as reduce but applies the callback from right most item first.
     *
     * @param callable $fn   The callback.
     * @param mixed    $memo The initial value carried over to each iteration and returned finally.
     *
     * @return mixed
     */
    public function reduceRight(callable $fn, $memo)
    {
        return \array_reduce(\array_reverse($this->data, true), $fn, $memo);
    }

    /**
     * Alias of reduceRight().
     */
    public function foldr(callable $fn, $memo)
    {
        return $this->reduceRight($fn, $memo);
    }

    /**
     * Find the first item (or index) that passes given truth test.
     *
     * @param callable $fn       The truth test callback.
     * @param boolean  $useValue Whether to return value or the index on match.
     *
     * @return mixed|null
     */
    public function find(callable $fn, $useValue = true)
    {
        foreach ($this->data as $index => $value) {
            if ($fn($value, $index)) {
                return $useValue ? $value : $index;
            }
        }
    }

    /**
     * Alias of find().
     */
    public function detect(callable $fn)
    {
        return $this->find($fn);
    }

    /**
     * Find and return all the items that passes given truth test.
     *
     * @param callable|string|null $fn The truth test callback.
     *
     * @return self
     */
    public function filter($fn = null)
    {
        if (null === $fn) {
            return new static(\array_filter($this->data));
        }

        $data = \array_filter($this->data, $fn, \ARRAY_FILTER_USE_BOTH);

        return new static($data);
    }

    /**
     * Alias of filter().
     */
    public function select(callable $fn = null)
    {
        return $this->filter($fn);
    }

    /**
     * Find and return all the items that fails given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return self
     */
    public function reject(callable $fn)
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
    public function every(callable $fn)
    {
        return $this->match($fn, true);
    }

    /**
     * Alias of every().
     */
    public function all(callable $fn)
    {
        return $this->every($fn);
    }

    /**
     * Tests if some (at least one) of the items pass given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return bool
     */
    public function some(callable $fn)
    {
        return $this->match($fn, false);
    }

    /**
     * Alias of some().
     */
    public function any(callable $fn)
    {
        return $this->some($fn);
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
    protected function match(callable $fn, $all = true)
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
     *
     * @param mixed $item
     *
     * @return bool
     */
    public function contains($item)
    {
        return \in_array($item, $this->data);
    }

    /**
     * Alias of contains().
     */
    public function includes($item)
    {
        return $this->contains($item);
    }

    /**
     * Invoke a callback using all of the items as arguments.
     *
     * @param callable $fn The callback.
     *
     * @return mixed Whatever the callback yields.
     */
    public function invoke(callable $fn)
    {
        return \call_user_func_array($fn, $this->data);
    }

    /**
     * Pluck given property from each of the items.
     *
     * @param string|int $columnKey
     * @param string|int $indexKey
     *
     * @return self
     */
    public function pluck($columnKey, $indexKey = null)
    {
        $data = \array_column($this->data, $columnKey, $indexKey);

        return new static($data);
    }

    /**
     * Filter only the items that contain all the given props (matching both index and value).
     *
     * @param array $props
     *
     * @return self
     */
    public function where(array $props)
    {
        return $this->filter($this->matcher($props));
    }

    /**
     * Get the first item that contains all the given props (matching both index and value).
     *
     * @param array $props
     *
     * @return mixed
     */
    public function findWhere(array $props)
    {
        return $this->find($this->matcher($props));
    }

    /**
     * Props matcher used by where() and findWhere().
     *
     * @internal
     *
     * @param array $props Key value pairs.
     *
     * @return true
     */
    protected function matcher(array $props)
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
    public function max($fn = null)
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
    public function min($fn = null)
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
    protected function maxMin($fn = null, $isMax = true)
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
    public function shuffle()
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
     * @param integer $n Number of items to include.
     *
     * @return self
     */
    public function sample($n = 1)
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
    public function sortBy($fn)
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
    public function groupBy($fn)
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
    public function indexBy($fn)
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
    public function countBy($fn)
    {
        return $this->group($fn, true)->map(function ($value) {
            return \count($value);
        });
    }

    /**
     * Group/index items by using the result of given callback.
     *
     * @internal
     *
     * @param callable|string $fn
     * @param bool            $isGroup
     *
     * @return self
     */
    protected function group($fn, $isGroup = true)
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
     *
     * @param callable|string $fn
     *
     * @return self
     */
    public function partition($fn)
    {
        $data = [[/* pass */], [/* fail */]];
        $fn   = $this->valueFn($fn);

        $this->each(function ($value, $index) use ($fn, &$data) {
            $data[$fn($value, $index) ? 0 : 1][] = $value;
        });

        return new static($data);
    }
}
