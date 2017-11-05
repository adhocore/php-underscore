<?php

\class_alias('Ahc\Underscore\Underscore', 'Ahc\Underscore');

if (!\function_exists('underscore')) {
    /**
     * Underscore instantiation helper.
     *
     * @param mixed $data
     *
     * @return Ahc\Underscore\Underscore
     */
    function underscore($data = [])
    {
        return new Ahc\Underscore\Underscore($data);
    }
}
