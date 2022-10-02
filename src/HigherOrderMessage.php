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
    public function __construct(protected UnderscoreBase $underscore, protected string $method)
    {
    }

    public function __call(string $method, array $args): mixed
    {
        return $this->underscore->{$this->method}(static fn ($item) => \call_user_func_array([$item, $method], $args));
    }

    public function __get($prop): mixed
    {
        return $this->underscore->{$this->method}(static fn ($item) => \array_column([$item], $prop)[0] ?? null);
    }
}
