<?php

namespace Ahc\Underscore;

class UnderscoreArray extends UnderscoreCollection
{
    /**
     * Get the first n items.
     *
     * @param int $n
     *
     * @return array
     */
    public function first($n = 1)
    {
        return $this->slice($n, true);
    }

    /**
     * Alias of first().
     *
     * @param int $n
     *
     * @return array
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
     * @return array
     */
    public function take($n = 1)
    {
        return $this->first($n);
    }

    /**
     * Get the last n items.
     *
     * @param int $n
     *
     * @return array
     */
    public function last($n = 1)
    {
        return $this->slice($n, false);
    }

    /**
     * Alias of last().
     *
     * @param int $n
     *
     * @return array
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
     * @return array
     */
    public function drop($n = 1)
    {
        return $this->last($n);
    }

    /**
     * Extracts n items from first or last.
     *
     * @internal
     *
     * @param int  $n
     * @param bool $isFirst From first if true, else last.
     *
     * @return array
     */
    protected function slice($n, $isFirst = true)
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
     *
     * @return self
     */
    public function compact()
    {
        return $this->filter(null);
    }

    /**
     * Gets the flattened version of multidimensional items.
     *
     * @return self
     */
    public function flatten()
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
    public function unique($fn = null)
    {
        if (null === $fn) {
            return new static(\array_unique($this->data));
        }

        $ids = [];
        $fn  = $this->valueFn($fn);

        return $this->filter(function ($value, $index) use ($fn, &$ids) {
            return !isset($ids[$id = $fn($value, $index)]) ? $ids[$id] = true : false;
        });
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
     * Get the items whose value is not in given data.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function difference($data)
    {
        $data = $this->asArray($data);

        return $this->filter(function ($value) use ($data) {
            return !\in_array($value, $data);
        });
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
     * Get the union/merger of items with given data.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function union($data)
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
    public function intersection($data)
    {
        $data = $this->asArray($data);

        return $this->filter(function ($value) use ($data) {
            return \in_array($value, $data);
        });
    }

    /**
     * Group the values from data and items having same indexes together.
     *
     * @param array|mixed $data Array or array like or array convertible.
     *
     * @return self
     */
    public function zip($data)
    {
        $data = $this->asArray($data);

        return $this->map(function ($value, $index) use ($data) {
            return [$value, isset($data[$index]) ? $data[$index] : null];
        });
    }

    /**
     * Hydrate the items into given class or stdClass.
     *
     * @param string|null $className FQCN of the class whose constructor accepts two parameters: value and index.
     *
     * @return self
     */
    public function object($className = null)
    {
        return $this->map(function ($value, $index) use ($className) {
            return $className ? new $className($value, $index) : (object) \compact('value', 'index');
        });
    }

    /**
     * Find the first index that passes given truth test.
     *
     * @param callable $fn The truth test callback.
     *
     * @return mixed|null
     */
    public function findIndex($fn = null)
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
    public function findLastIndex($fn = null)
    {
        return (new static(\array_reverse($this->data, true)))->find($this->valueFn($fn), false);
    }

    /**
     * Find the first index of given value if available null otherwise.
     *
     * @param callable $fn The truth test callback.
     *
     * @return string|int|null
     */
    public function indexOf($value)
    {
        return (false === $index = \array_search($value, $this->data)) ? null : $index;
    }

    /**
     * Find the last index of given value if available null otherwise.
     *
     * @param callable $fn The truth test callback.
     *
     * @return string|int|null
     */
    public function lastIndexOf($value)
    {
        return (false === $index = \array_search($value, \array_reverse($this->data, true))) ? null : $index;
    }

    /**
     * Gets the smallest index at which an object should be inserted so as to maintain order.
     *
     * Note that the initial stack must be sorted already.
     *
     * @param $object             The new object which needs to be adjusted in stack.
     * @param callable|string $fn The comparator callback.
     *
     * @return string|int|null
     */
    public function sortedIndex($object, $fn)
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
    public function range($start, $stop, $step = 1)
    {
        return new static(\range($start, $stop, $step));
    }
}
