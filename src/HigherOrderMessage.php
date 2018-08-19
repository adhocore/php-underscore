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

/**
 * Source: Laravel HigherOrderProxy.
 *
 * @link https://github.com/laravel/framework/pull/16267
 */
class HigherOrderMessage
{
    protected $underscore;
    protected $method;

    public function __construct(UnderscoreBase $underscore, $method)
    {
        $this->underscore = $underscore;
        $this->method     = $method;
    }

    public function __call($method, $args)
    {
        return $this->underscore->{$this->method}(function ($item) use ($method, $args) {
            return \call_user_func_array([$item, $method], $args);
        });
    }

    public function __get($prop)
    {
        return $this->underscore->{$this->method}(function ($item) use ($prop) {
            $props = \array_column([$item], $prop);

            return $props ? $props[0] : null;
        });
    }
}
