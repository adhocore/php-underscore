<?php

/*
 * This file is part of the PHP-UNDERSCORE package.
 *
 * (c) Jitendra Adhikari <jiten.adhikary@gmail.com>
 *     <https://github.com/adhocore>
 *
 * Licensed under MIT license.
 */

use Ahc\Underscore\Underscore;

\class_alias(Underscore::class, 'Ahc\\Underscore');

if (!\function_exists('underscore')) {
    /**
     * Underscore instantiation helper.
     *
     * @param mixed $data
     *
     * @return Underscore
     */
    function underscore(mixed $data = []): Underscore
    {
        return new Underscore($data);
    }
}
