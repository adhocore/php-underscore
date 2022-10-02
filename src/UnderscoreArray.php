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

class UnderscoreArray extends UnderscoreCollection
{
    /**
     * Get the first n items.
     *
     * @param int $n
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    public function first(int $n = 1): mixed
    {
        return $this->slice($n, true);
    }

    /**
     * Get the last n items.
     *
     * @param int $n
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    public function last(int $n = 1): mixed
    {
        return $this->slice($n, false);
    }

    /**
     * Extracts n items from first or last.
     *
     * @internal
     *
     * @param int  $n
     * @param bool $isFirst From first if true, else last.
     *
     * @return array|mixed With n = 1 (default), it gives one item, which may not be array.
     */
    protected function slice(int $n, bool $isFirst = true): mixed
    {
        if ($n < 2) {
            return $isFirst ? \reset($this->data) : \end($this->data);
        }

        if ($n >= $c = $this->count()) {
            return $this->data;
        }

        return \array_slice($this->data, $isFirst ? 0 : $c - $n, $isFirst ? $n : null, true);
    }

    /**
     * Get only the truthy items.
     */
    public function compact(): self
    {
        return $this->filter(null);
    }

    /**
     * Gets the flattened version of multidimensional items.
     */
    public function flatten(): self
    {
        return new static($this->flat($this->data));
    }

    /**
     * Gets the unique items using the id resulted from callback.
     *
     * @param callable|string $fn The callback. String is resolved to value of that index.
     *
     * @return self
     */
    public function unique(callable $fn = null): self
    {
        if (null === $fn) {
            return new static(\array_unique($this->data));
        }

        $ids = [];
        $fn  = $this->valueFn($fn);

        return $this->filter(static function ($value, $index) use ($fn, &$ids) {
            return !isset($ids[$id = $fn($value, $index)]) ? $ids[$id] = true : false;
        });
    }

    /**
     * Get the items whose value is not in given data.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function difference(mixed $data): self
    {
        $data = $this->asArray($data);

        return $this->filter(static fn ($value) => !\in_array($value, $data));
    }

    /**
     * Get the union/merger of items with given data.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function union(mixed $data): self
    {
        return new static(\array_merge($this->data, $this->asArray($data)));
    }

    /**
     * Gets the items whose value is common with given data.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function intersection(mixed $data): self
    {
        $data = $this->asArray($data);

        return $this->filter(static fn ($value) => \in_array($value, $data));
    }

    /**
     * Group the values from data and items having same indexes together.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function zip(mixed $data): self
    {
        $data = $this->asArray($data);

        return $this->map(static fn ($value, $idx) => [$value, isset($data[$idx]) ? $data[$idx] : null]);
    }

    /**
     * Hydrate the items into given class or stdClass.
     *
     * @param string|null $class FQCN of the class whose constructor accepts two parameters: value and index.
     *
     * @return self
     */
    public function object(string $class = null): self
    {
        return $this->map(
            static fn ($value, $index) => $class ? new $class($value, $index) : (object) \compact('value', 'index')
        );
    }

    /**
     * Find the first index that passes given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return mixed|null
     */
    public function findIndex(callable $fn = null): mixed
    {
        return $this->find($this->valueFn($fn), false);
    }

    /**
     * Find the last index that passes given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return mixed|null
     */
    public function findLastIndex(callable $fn = null): mixed
    {
        return (new static(\array_reverse($this->data, true)))->find($this->valueFn($fn), false);
    }

    /**
     * Find the first index of given value if available null otherwise.
     *
     * @param mixed $value The lookup value.
     *
     * @return string|int|null
     */
    public function indexOf(mixed $value): mixed
    {
        return (false === $index = \array_search($value, $this->data)) ? null : $index;
    }

    /**
     * Find the last index of given value if available null otherwise.
     *
     * @param mixed $value The lookup value.
     *
     * @return string|int|null
     */
    public function lastIndexOf(mixed $value): mixed
    {
        return (false === $index = \array_search($value, \array_reverse($this->data, true))) ? null : $index;
    }

    /**
     * Gets the smallest index at which an object should be inserted so as to maintain order.
     *
     * Note that the initial stack must be sorted already.
     *
     * @param mixed           $object The new object which needs to be adjusted in stack.
     * @param callable|string $fn     The comparator callback.
     *
     * @return string|int|null
     */
    public function sortedIndex(mixed $object, callable $fn = null): mixed
    {
        $low   = 0;
        $high  = $this->count();
        $data  = $this->values();
        $fn    = $this->valueFn($fn);
        $value = $fn($object);
        $keys  = $this->keys();

        while ($low < $high) {
            $mid = \intval(($low + $high) / 2);
            if ($fn($data[$mid]) < $value) {
                $low = $mid + 1;
            } else {
                $high = $mid;
            }
        }

        return isset($keys[$low]) ? $keys[$low] : null;
    }

    /**
     * Creates a new range from start to stop with given step.
     *
     * @param int $start
     * @param int $stop
     * @param int $step
     *
     * @return self
     */
    public function range(int $start, int $stop, int $step = 1): self
    {
        return new static(\range($start, $stop, $step));
    }
}
