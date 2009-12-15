<?php

if ($argc != 3) die('Usage: ' . $argv[0] . ' <directory> <phar archive>' . PHP_EOL . PHP_EOL);

$phar = new Phar($argv[2]);
$phar->buildFromDirectory($argv[1]);
$phar->setAlias('this.phar');

$phar->setStub('<?php require \'phar://this.phar/Autoload.php\'; __HALT_COMPILER(); ?>');

print 'Created Phar archive ' . $argv[2] . ' from ' . $argv[1] . PHP_EOL;

?>
