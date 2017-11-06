<?php

namespace Ahc\Underscore;

class UnderscoreArray extends UnderscoreCollection
{
    public function first($n = 1)
    {
        return $this->slice($n, true);
    }

    public function head($n = 1)
    {
        return $this->first($n);
    }

    public function take($n = 1)
    {
        return $this->first($n);
    }

    public function last($n = 1)
    {
        return $this->slice($n, false);
    }

    public function tail($n = 1)
    {
        return $this->last($n);
    }

    public function drop($n = 1)
    {
        return $this->last($n);
    }

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

    public function compact()
    {
        return new static(\array_filter($this->data));
    }

    public function flatten()
    {
        return new static($this->flat($this->data));
    }

    public function unique($fn = null)
    {
        if (null === $fn) {
            return new static(\array_unique($this->data));
        }

        $ids = $data = [];
        $fn  = $this->valueFn($fn);

        return $this->filter(function ($value, $index) use ($fn, &$ids) {
            return !isset($ids[$id = $fn($value, $index)]) ? $ids[$id] = true : false;
        });
    }

    public function uniq($fn = null)
    {
        return $this->unique($fn);
    }
}
