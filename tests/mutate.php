<?php

declare(strict_types=1);

// Resolve test files before the mutation and Patchwork stream wrappers start.
$root = dirname(__DIR__);
$files = glob(__DIR__.'/Unit/*Test.php');
if ($files === false || $files === []) {
    fwrite(STDERR, "No unit test files found.\n");
    exit(2);
}

sort($files);
$options = array_slice($_SERVER['argv'], 1);
foreach ($options as $option) {
    $name = explode('=', $option, 2)[0];
    foreach (['--processes', '--parallel', '--configuration', '--no-configuration'] as $reserved) {
        if (str_starts_with($name, '--') && $name !== '--' && str_starts_with($reserved, $name)) {
            fwrite(STDERR, "Process-count, parallel, and configuration options are managed by this launcher.\n");
            exit(2);
        }
    }

    if (str_starts_with($option, '-p') || str_starts_with($option, '-c')) {
        fwrite(STDERR, "Short parallel and configuration flags are managed by this launcher.\n");
        exit(2);
    }
}

if (! chdir($root)) {
    fwrite(STDERR, "Cannot enter the repository root.\n");
    exit(2);
}

// XML file entries avoid directory discovery and ParaTest's single-path limit.
$configuration = new DOMDocument;
$configuration->load($root.'/phpunit.xml');
$xpath = new DOMXPath($configuration);
$unitSuite = $xpath->query('/phpunit/testsuites/testsuite[@name="Unit"]')->item(0);
if ($unitSuite === null) {
    throw new RuntimeException('The Unit testsuite is missing.');
}

while ($unitSuite->firstChild !== null) {
    $unitSuite->removeChild($unitSuite->firstChild);
}

foreach ($files as $file) {
    $entry = $configuration->createElement('file');
    $entry->appendChild($configuration->createTextNode($file));
    $unitSuite->appendChild($entry);
}

$settings = $configuration->documentElement;
$settings->setAttribute('bootstrap', $root.'/tests/bootstrap.php');
$settings->setAttribute('recordTestRunHistory', 'false');
$settings->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:noNamespaceSchemaLocation', $root.'/vendor/phpunit/phpunit/phpunit.xsd');
foreach ($xpath->query('/phpunit/source/include/* | /phpunit/source/exclude/*') as $entry) {
    $entry->textContent = $root.'/'.ltrim($entry->textContent, './');
}

// Isolated PHPUnit tests also map source files after the stream wrappers start.
foreach ($xpath->query('/phpunit/source/include/directory | /phpunit/source/exclude/directory') as $directory) {
    $prefix = $directory->getAttribute('prefix');
    $suffix = $directory->hasAttribute('suffix') ? $directory->getAttribute('suffix') : '.php';
    $paths = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory->textContent, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile() && str_starts_with($file->getFilename(), $prefix) && str_ends_with($file->getFilename(), $suffix)) {
            $paths[] = $file->getPathname();
        }
    }

    sort($paths);
    foreach ($paths as $path) {
        $entry = $configuration->createElement('file');
        $entry->appendChild($configuration->createTextNode($path));
        $directory->parentNode->insertBefore($entry, $directory);
    }

    $directory->parentNode->removeChild($directory);
}

$configurationPath = tempnam(sys_get_temp_dir(), 'uams-mutation-');
if ($configurationPath === false) {
    throw new RuntimeException('Cannot create the mutation configuration file.');
}

if ($configuration->save($configurationPath) === false) {
    throw new RuntimeException('Cannot save the explicit-file mutation configuration.');
}

$arguments = [
    $root.'/vendor/bin/pest',
    '--configuration', $configurationPath,
    '--mutate', '--everything', '--parallel', '--no-cache', '--min=100',
    '--path=functions.php,home.php,page_archive.php,page_blog.php,search.php,templates',
    ...$options,
];
$_SERVER['argv'] = $GLOBALS['argv'] = $arguments;
$_SERVER['argc'] = $GLOBALS['argc'] = count($arguments);
require $root.'/vendor/bin/pest';
