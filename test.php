<?php
require_once 'vendor/autoload.php'; // Autoload files using Composer autoload

$x = new Ip2Location\Ip2Location();

echo $x->test();