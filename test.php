<?php

var_dump('before');

require __DIR__ . '/_build/JobQueue.phar';

var_dump('after');

$q = \spriebsch\JobQueue\Queue();
var_dump($q);

?>
