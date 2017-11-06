<?php

namespace Ahc\Underscore;

class UnderscoreCollection extends UnderscoreBase
{
    public function each(callable $fn)
    {
        foreach ($this->data as $index => $value) {
            $fn($value, $index);
        }

        return $this;
    }

    public function map(callable $fn)
    {
        $data = [];

        foreach ($this->data as $index => $value) {
            $data[$index] = $fn($value, $index);
        }

        return new static($data);
    }

    public function collect(callable $fn)
    {
        return $this->map($fn);
    }

    public function reduce(callable $fn, $memo)
    {
        return \array_reduce($this->data, $fn, $memo);
    }

    public function foldl(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    public function inject(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    public function reduceRight(callable $fn, $memo)
    {
        return \array_reduce(\array_reverse($this->data, true), $fn, $memo);
    }

    public function foldr(callable $fn, $memo)
    {
        return $this->reduceRight($fn, $memo);
    }

    public function find(callable $fn, $useValue = true)
    {
        foreach ($this->data as $index => $value) {
            if ($fn($value, $index)) {
                return $useValue ? $value : $index;
            }
        }
    }

    public function detect(callable $fn)
    {
        return $this->find($fn);
    }

    public function filter($fn = null)
    {
        if (null === $fn) {
            return new static(\array_filter($this->data));
        }

        $data = \array_filter($this->data, $fn, \ARRAY_FILTER_USE_BOTH);

        return new static($data);
    }

    public function select(callable $fn)
    {
        return $this->filter($fn);
    }

    public function reject(callable $fn)
    {
        $data = \array_filter($this->data, $this->negate($fn), \ARRAY_FILTER_USE_BOTH);

        return new static($data);
    }

    public function every(callable $fn)
    {
        return $this->match($fn, true);
    }

    public function all(callable $fn)
    {
        return $this->every($fn);
    }

    public function some(callable $fn)
    {
        return $this->match($fn, false);
    }

    public function any(callable $fn)
    {
        return $this->some($fn);
    }

    protected function match(callable $fn, $all = true)
    {
        foreach ($this->data as $index => $value) {
            if ($all ^ $fn($value, $index)) {
                return !$all;
            }
        }

        return $all;
    }

    public function contains($item)
    {
        return \in_array($item, $this->data);
    }

    public function includes($item)
    {
        return $this->contains($item);
    }

    public function invoke(callable $fn)
    {
        return \call_user_func_array($fn, $this->data);
    }

    public function pluck($columnKey, $indexKey = null)
    {
        $data = \array_column($this->data, $columnKey, $indexKey);

        return new static($data);
    }

    public function where(array $props)
    {
        return $this->filter($this->matcher($props));
    }

    public function findWhere(array $props)
    {
        return $this->find($this->matcher($props));
    }

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

    public function max($fn = null)
    {
        return $this->maxMin($fn, true);
    }

    public function min($fn = null)
    {
        return $this->maxMin($fn, false);
    }

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

    public function sample($n = 1)
    {
        $shuffled = $this->shuffle()->get();

        return new static(\array_slice($shuffled, 0, $n, true));
    }

    public function sortBy($fn)
    {
        $data = $this->map($this->valueFn($fn))->get();

        \asort($data); // Keep keys.

        foreach ($data as $index => $value) {
            $data[$index] = $this->data[$index];
        }

        return new static($data);
    }

    public function groupBy($fn)
    {
        return $this->group($fn, true);
    }

    public function indexBy($fn)
    {
        return $this->group($fn, false);
    }

    public function countBy($fn)
    {
        return $this->group($fn, true)->map(function ($value) {
            return \count($value);
        });
    }

    protected function group($fn, $isGroup = true)
    {
        $data = [];
        $fn   = $this->valueFn($fn);

        foreach ($this->data as $index => $value) {
            $isGroup ? $data[$fn($value, $index)][$index] = $value : $data[$fn($value, $index)] = $value;
        }

        return new static($data);
    }

    public function size()
    {
        return $this->count();
    }

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
