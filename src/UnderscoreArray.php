<?php

namespace Ahc\Underscore;

class UnderscoreArray extends UnderscoreBase
{
    public function first()
    {
        return \reset($this->data);
    }

    public function last()
    {
        return \end($this->data);
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

        foreach ($this->data as $index => $value) {
            if (!isset($ids[$id = $fn($value, $index)])) {
                $ids[$id] = true;

                $data[$index] = $value;
            }
        }

        return new static($data);
    }

    public function uniq($fn)
    {
        return $this->unique($fn);
    }
}
