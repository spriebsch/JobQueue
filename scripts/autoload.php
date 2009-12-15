<?php

if ($argc != 3) die('Usage: ' . $argv[0] . ' <directory> <outfile>' . PHP_EOL . PHP_EOL);

$path = $argv[1];

print 'Creating Autoloader for ' . $path . PHP_EOL;

require __DIR__ . '/Autoload/classfinder.php';
require __DIR__ . '/Autoload/phpfilter.php';
require __DIR__ . '/Autoload/autoloadbuilder.php';
require __DIR__ . '/DirectoryScanner/directoryscanner.php';
require __DIR__ . '/DirectoryScanner/filesonlyfilter.php';
require __DIR__ . '/DirectoryScanner/includeexcludefilter.php';

$scanner = new \TheSeer\Tools\DirectoryScanner;
$scanner->addInclude('*.php');

$finder = new \TheSeer\Tools\ClassFinder;

$found = $finder->parseMulti($scanner($path));

$ab = new \TheSeer\Tools\AutoloadBuilder($found, $path);
$ab->omitClosingTag(false);

file_put_contents($argv[2], $ab->render());

print 'Autoloader written to ' . $argv[2] . PHP_EOL;

?>
