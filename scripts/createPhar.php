<?php

$src = __DIR__ . '/../src';
$dir = __DIR__ . '/../_phar';
$file = __DIR__ . '/../_phar/JobQueue.phar';
$stub = __DIR__ . '/../templates/PharStub.php';

if (ini_get('Phar.readonly')) {
    die('Error: Cannot create Phar. "Phar.readonly" must be set to Off in php.ini' . PHP_EOL);
}

if (!Phar::isValidPharFilename($file)) {
    die('Filename "' . $filename . '" is not a valid Phar filename' . PHP_EOL);
}

if (!file_exists($dir)) {
    mkdir($dir);
}

if (file_exists($file)) {
    unlink($file);
}

$phar = new Phar($file, 0, 'JobQueue.phar');
$phar->buildFromDirectory($src);
$phar->setStub(file_get_contents($stub));

var_dump($phar);

print 'Phar file ' . basename($file) . ' created.' . PHP_EOL;

?>
