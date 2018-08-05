<?php

/*
 * This file is part of the PHP-UNDERSCORE package.
 *
 * (c) Jitendra Adhikari <jiten.adhikary@gmail.com>
 *     <https://github.com/adhocore>
 *
 * Licensed under MIT license.
 */

require_once __DIR__ . '/../vendor/autoload.php';

if (class_exists('\PHPUnit_Framework_Error_Notice')) {
    class_alias('\PHPUnit_Framework_Error_Notice', '\PHPUnit\Framework\Error\Notice');
}
