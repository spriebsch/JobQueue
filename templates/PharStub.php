<?php

error_reporting(E_ALL);

var_dump(1);

Phar::mapPhar('JobQueue.phar');

var_dump(2);

require 'phar://JobQueue.phar/Autoload.php';

var_dump(3);

__HALT_COMPILER();

?>
