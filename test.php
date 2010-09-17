<?php

require __DIR__ . '/_build/JobQueue.phar';

$q = new spriebsch\JobQueue\Queue(sys_get_temp_dir() . '/testqueue');
var_dump($q);

?>
