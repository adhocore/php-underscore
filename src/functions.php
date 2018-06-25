<?php

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
    function underscore($data = [])
    {
        return new Underscore($data);
    }
}
