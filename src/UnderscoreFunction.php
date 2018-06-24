<?php

namespace Ahc\Underscore;

class UnderscoreFunction extends UnderscoreArray
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

    /**
     * Returns a function that is the composition of a list of functions,
     * each consuming the return value of the function that follows.
     *
     * Note that last function is executed first.
     *
     * @param callable         $fn1
     * @param callable         $fn2
     * @param ...callable|null $fn3 And so on!
     *
     * @return mixed Final result value.
     */
    public function compose(callable $fn1, callable $fn2 /* , callable $fn3 = null */)
    {
        $fns   = \func_get_args();
        $start = \func_num_args() - 1;

        return function () use ($fns, $start) {
            $i      = $start;
            $result = \call_user_func_array($fns[$start], \func_get_args());

            while ($i--) {
                $result = $fns[$i]($result);
            }

            return $result;
        };
    }
}
