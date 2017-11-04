<?php

namespace Ahc\Underscore;

class Helper
{
    /**
     * Get data as array.
     *
     * @param mixed $data
     *
     * @return array
     */
    public static function asArray($data)
    {
        if (\is_array($data)) {
            return $data;
        }

        if (\method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        if ($data instanceof Underscore) {
            return $data->get();
        }

        if ($data instanceof \Traversable) {
            return \iterator_to_array($data);
        }

        if ($data instanceof \JsonSerializable) {
            return $data->jsonSerialize();
        }

        return (array) $data;
    }
}
