<?php

\class_alias('Ahc\Underscore\Underscore', 'Ahc\Underscore');

if (!\function_exists('underscore')) {
    function underscore($data)
    {
        return new Ahc\Underscore\Underscore($data);
    }
}
