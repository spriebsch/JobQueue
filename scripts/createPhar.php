<?php

$dir = __DIR__ . '/../_phar';
$file = __DIR__ . '/../_phar/JobQueue.phar';
$stub = __DIR__ . '/../templates/PharStub.php';

if (ini_get('Phar.readonly')) {
    die('Error: Cannot create Phar. "Phar.readonly" must be set to Off in php.ini' . PHP_EOL);
}

if ($argc < 3) {
    die('Usage : ' . $argv[0] . ' <src> <filename> [<stub>]' . PHP_EOL);
}

$src = $argv[1];
$file = $argv[2];

if (isset($argv[3])) {
    $stub = $argv[3];
} else {
    $stub = __DIR__ . '/../templates/PharStub.php';
}

$stub = realpath($stub);
$dir = realpath(dirname($file));

print 'Creating Phar file "' . $file . '"' . PHP_EOL . 
      'from directory "' . $src . '"' . PHP_EOL .
      'based on stub "' . $stub . '"' . PHP_EOL . PHP_EOL;

if (!Phar::isValidPharFilename($file)) {
    die('Filename "' . $file . '" is not a valid Phar filename' . PHP_EOL);
}

if (!file_exists($dir)) {
    mkdir($dir);
    print 'Created target directory ' . $dir . PHP_EOL;
}

if (file_exists($file)) {
    unlink($file);
    print 'Deleted existing file ' . $file . PHP_EOL;
}

$phar = new Phar($file, 0, 'JobQueue.phar');
$phar->buildFromDirectory($src);
$phar->setStub(file_get_contents($stub));

print 'Wrote Phar file' . PHP_EOL . PHP_EOL;

print 'Done.' . PHP_EOL;

?>
