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

trait Arrayizes
{
    /**
     * Get data as array.
     *
     * @param mixed $data Arbitrary data.
     * @param bool  $cast Force casting to array!
     *
     * @return array
     */
    public function asArray($data, $cast = true)
    {
        if (\is_array($data)) {
            return $data;
        }

        if ($data instanceof static) {
            return $data->get();
        }

        // @codeCoverageIgnoreStart
        if ($data instanceof \Traversable) {
            return \iterator_to_array($data);
        }
        // @codeCoverageIgnoreEnd

        if ($data instanceof \JsonSerializable) {
            return $data->jsonSerialize();
        }

        if (\method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        return  $cast ? (array) $data : $data;
    }

    /**
     * Convert the data items to array.
     *
     * @return array
     */
    public function toArray()
    {
        return \array_map(function ($value) {
            if (\is_scalar($value)) {
                return $value;
            }

            return $this->asArray($value, false);
        }, $this->getData());
    }
}
