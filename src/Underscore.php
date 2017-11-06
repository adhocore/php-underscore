<?php

namespace Ahc\Underscore;

final class Underscore extends UnderscoreArray
{
    public function bind(\Closure $fn, $ctx)
    {
        \Closure::bind($fn, \is_object($ctx) ? $ctx : null);
    }

    public function memoize(callable $fn)
    {
        static $memo = [];

        $hash = \md5(\json_encode(\array_slice(\func_get_args(), 1)));

        if (isset($memo[$hash])) {
            return $memo[$hash];
        }

        return $memo[$hash] = \call_user_func_array($fn, $args);
    }

    public function delay(callable $fn, $wait)
    {
        usleep(1000 * $wait);

        return \call_user_func_array($fn, \array_slice(\func_get_args(), 2));
    }

    public function throttle($fn, $wait)
    {
        $previous = 0;
        $result   = null;

        return function () use ($fn, &$previous, &$result, &$wait) {
            $now  = $this->now();
            $args = \func_get_args();

            if (!$previous) {
                $previous = $now;
            }

            $remaining = $wait - ($now - $previous);

            if ($remaining <= 0 || $remaining > $wait) {
                $previous = $now;
                $result   = \call_user_func_array($fn, $args);
            }

            return $result;
        };
    }
}
