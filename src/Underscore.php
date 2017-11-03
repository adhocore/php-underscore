<?php

namespace Ahc\Underscore;

class Underscore implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    const VERSION = '0.0.1';

    /** @var array The array manipulated by this object */
    protected $data;

    /**
     * Constructor.
     *
     * @param array|mixed $data.
     */
    public function __construct($data = [])
    {
        $this->data = is_array($data) ? $data : Helper::asArray($data);
    }

    /**
     * Get the underlying array data.
     *
     * @param string|int|null $index
     *
     * @return mixed
     */
    public function get($index = null)
    {
        if (null === $index) {
            return $this->data;
        }

        return $this->data[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($index)
    {
        return \array_key_exists($index, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($index)
    {
        return $this->data[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($index)
    {
        unset($this->data[$index]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return \json_encode($this->data);
    }
}

\class_alias('Ahc\Underscore\Underscore', 'Ahc\Underscore');
