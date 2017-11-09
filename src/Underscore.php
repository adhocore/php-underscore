<?php

namespace Ahc\Underscore;

final class Underscore extends UnderscoreArray
{
    /**
     * Returns a callable which when invoked caches the result for given arguments
     * and reuses that result in subsequent calls.
     *
     * @param callable $fn The main callback.
     *
     * @return mixed
     */
    public function memoize(callable $fn)
    {
        static $memo = [];

        return function () use (&$memo, $fn) {
            $hash = \md5(\json_encode($args = \func_get_args()));

            if (isset($memo[$hash])) {
                return $memo[$hash];
            }

            return $memo[$hash] = \call_user_func_array($fn, $args);
        };
    }

    /**
     * Cache the result of callback for given arguments and reuse that in subsequent call.
     *
     * @param callable $fn   The main callback.
     * @param int      $wait The time to wait in millisec.
     *
     * @return mixed
     */
    public function delay(callable $fn, $wait)
    {
        return function () use ($fn, $wait) {
            \usleep(1000 * $wait);

            return \call_user_func_array($fn, \func_get_args());
        };
    }

    /**
     * Returns a callable that wraps given callable which can be only invoked
     * at most once per given $wait threshold.
     *
     * @param callable $fn   The main callback.
     * @param int      $wait The callback will only be triggered at most once within this period.
     *
     * @return mixed The return set of callback if runnable else the previous cache.
     */
    public function throttle(callable $fn, $wait)
    {
        static $previous = 0;
        static $result   = null;

        return function () use ($fn, &$previous, &$result, &$wait) {
            $now = $this->now();

            if (!$previous) {
                $previous = $now;
            }

            $remaining = $wait - ($now - $previous);

            if ($remaining <= 0 || $remaining > $wait) {
                $previous = $now;
                $result   = \call_user_func_array($fn, \func_get_args());
            }

            return $result;
        };
    }
}
