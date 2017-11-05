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

        if ($data instanceof Underscore) {
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

        return (array) $data;
    }
}
