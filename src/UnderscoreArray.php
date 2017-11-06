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

    public function difference($data)
    {
        return new static(\array_diff($this->data, $this->asArray($data)));
    }

    public function without($data)
    {
        return $this->difference($data);
    }

    public function union($data)
    {
        return new static(\array_unique(
            \array_merge($this->data, $this->asArray($data))
        ));
    }

    public function intersection($data)
    {
        $data = $this->asArray($data);

        return $this->filter(function ($value, $index) use ($data) {
            return \in_array($value, $data);
        });
    }

    public function zip($data)
    {
        $data = $this->asArray($data);

        return $this->map(function ($value, $index) use ($data) {
            return [$value, isset($data[$index]) ? $data[$index] : null];
        });
    }

    public function unzip()
    {
        //
    }

    public function object($className = null)
    {
        return $this->map(function ($value, $index) {
            return $className ? new $className($value, $index) : (object) \compact('value', 'index');
        });
    }

    public function firstIndex($fn)
    {
        return $this->find($fn, false);
    }

    public function lastIndex($fn)
    {
        return (new static(\array_reverse($this->data, true)))->find($fn, false);
    }

    public function indexOf($value)
    {
        return \array_search($value, $this->data);
    }

    public function lastIndexOf($value)
    {
        return \array_search($value, \array_reverse($this->data, true));
    }

    public function range($start, $stop, $step = 1)
    {
        return new static(\range($start, $stop, $step));
    }
}
