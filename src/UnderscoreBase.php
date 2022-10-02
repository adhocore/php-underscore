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

class UnderscoreBase implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    use Arrayizes;
    use UnderscoreAliases;

    const VERSION = '0.0.2';

    /** @var array The array manipulated by this Underscore instance */
    protected array $data;

    /** @var array Custom userland functionality through named callbacks */
    protected static array $mixins = [];

    /**
     * Constructor. Only allow `Ahc\Underscore\Underscore` to be instantiated in userland.
     *
     * @param array|mixed $data Array or array like or array convertible.
     */
    protected function __construct($data = [])
    {
        $this->data = \is_array($data) ? $data : $this->asArray($data);
    }

    /**
     * Get the underlying array data.
     *
     * @param string|int|null $index
     *
     * @return mixed
     */
    public function get(mixed $index = null): mixed
    {
        if (null === $index) {
            return $this->data;
        }

        return $this->data[$index] ?? null;
    }

    /**
     * Get data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Flatten a multi dimension array to 1 dimension.
     */
    public function flat(array $array, &$flat = []): array
    {
        foreach ($array as $value) {
            if ($value instanceof static) {
                $value = $value->get();
            }

            if (\is_array($value)) {
                $this->flat($value, $flat);
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }

    /**
     * Negate a given truth test callable.
     */
    protected function negate(callable $fn): callable
    {
        return static fn () => !$fn(...\func_get_args());
    }

    /**
     * Get a value generator callable.
     *
     * @param callable|string|null $fn
     *
     * @return callable
     */
    protected function valueFn($fn = null): callable
    {
        if (\is_callable($fn)) {
            return $fn;
        }

        return static fn ($value) => null === $fn ? $value : \array_column([$value], $fn)[0] ?? null;
    }

    /**
     * Checks if offset/index exists.
     *
     * @param string|int $index
     *
     * @return bool
     */
    public function offsetExists(mixed $index): bool
    {
        return \array_key_exists($index, $this->data);
    }

    /**
     * Gets the value at given offset/index.
     *
     * @return mixed
     */
    public function offsetGet(mixed $index): mixed
    {
        return $this->data[$index];
    }

    /**
     * Sets a new value at the given offset/index.
     *
     * @param string|int $index
     * @param mixed      $value
     *
     * @return void
     */
    public function offsetSet(mixed $index, mixed $value): void
    {
        $this->data[$index] = $value;
    }

    /**
     * Unsets/removes the value at given index.
     *
     * @param string|int $index
     *
     * @return void
     */
    public function offsetUnset(mixed $index): void
    {
        unset($this->data[$index]);
    }

    /**
     * Gets the count of items.
     */
    public function count(): int
    {
        return \count($this->data);
    }

    /**
     * Gets the iterator for looping.
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Gets the data for json serialization.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Stringify the underscore instance.
     */
    public function __toString(): string
    {
        return \json_encode($this->toArray());
    }

    /**
     * The current time in millisec.
     *
     * @return float
     */
    public function now(): float
    {
        return \microtime(1) * 1000;
    }

    /**
     * Get all the keys.
     */
    public function keys(): self
    {
        return new static(\array_keys($this->data));
    }

    /**
     * Get all the keys.
     */
    public function values(): self
    {
        return new static(\array_values($this->data));
    }

    /**
     * Pair all items to use an array of index and value.
     */
    public function pairs(): self
    {
        $pairs = [];

        foreach ($this->data as $index => $value) {
            $pairs[$index] = [$index, $value];
        }

        return new static($pairs);
    }

    /**
     * Swap index and value of all the items. The values should be stringifiable.
     */
    public function invert(): self
    {
        return new static(\array_flip($this->data));
    }

    /**
     * Pick only the items having one of the whitelisted indexes.
     *
     * @param array|...string|...int $index Either whitelisted indexes as array or as variads.
     *
     * @return self
     */
    public function pick($index): self
    {
        $indices = \array_flip(\is_array($index) ? $index : \func_get_args());

        return new static(\array_intersect_key($this->data, $indices));
    }

    /**
     * Omit the items having one of the blacklisted indexes.
     *
     * @param array|...string|...int $index Either blacklisted indexes as array or as variads.
     *
     * @return self
     */
    public function omit($index): self
    {
        $indices = \array_diff(
            \array_keys($this->data),
            \is_array($index) ? $index : \func_get_args()
        );

        return $this->pick($indices);
    }

    /**
     * Creates a shallow copy.
     */
    public function clon(): self
    {
        return clone $this;
    }

    /**
     * Invokes callback fn with clone and returns original self.
     */
    public function tap(callable $fn): self
    {
        $fn($this->clon());

        return $this;
    }

    /**
     * Adds a custom handler/method to instance. The handler is bound to this instance.
     */
    public static function mixin(string $name, \Closure $fn): void
    {
        static::$mixins[$name] = $fn;
    }

    /**
     * Calls the registered mixin by its name.
     */
    public function __call(string $method, array $args): self
    {
        if (isset(static::$mixins[$method])) {
            $method = \Closure::bind(static::$mixins[$method], $this);

            return $method($args);
        }

        throw new UnderscoreException("The mixin with name '$method' is not defined");
    }

    /**
     * Facilitates the use of Higher Order Messaging.
     */
    public function __get(string $method): HigherOrderMessage
    {
        // For now no mixins in HOM :)
        if (!\method_exists($this, $method)) {
            throw new UnderscoreException("The '$method' is not defined");
        }

        return new HigherOrderMessage($this, $method);
    }

    /**
     * Get string value (JSON representation) of this instance.
     */
    public function valueOf(): string
    {
        return (string) $this;
    }
}
