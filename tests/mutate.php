<?php

declare(strict_types=1);

// Keep test discovery outside the mutation/Patchwork stream wrappers.
$root = dirname(__DIR__);
$files = glob(__DIR__.'/Unit/*Test.php');
if ($files === false || $files === []) {
    fwrite(STDERR, "No unit test files found.\n");
    exit(2);
}

sort($files);
$options = array_slice($_SERVER['argv'], 1);
foreach ($options as $option) {
    if ($option === '--processes' || str_starts_with($option, '--processes=')) {
        fwrite(STDERR, "Do not pass --processes: the locked mutation tool leaks it into child runs.\n");
        exit(2);
    }

    if ($option === '--parallel' || str_starts_with($option, '--parallel=')) {
        fwrite(STDERR, "Use serial mutation runs: the parallel runner cannot accept the explicit test-file list.\n");
        exit(2);
    }
}

if (! chdir($root)) {
    fwrite(STDERR, "Cannot enter the repository root.\n");
    exit(2);
}

$arguments = [
    $root.'/vendor/bin/pest',
    ...$files,
    '--mutate', '--everything', '--no-cache', '--min=100',
    '--path=functions.php,home.php,page_archive.php,page_blog.php,search.php,templates',
    ...$options,
];
$_SERVER['argv'] = $GLOBALS['argv'] = $arguments;
$_SERVER['argc'] = $GLOBALS['argc'] = count($arguments);
require $root.'/vendor/bin/pest';
