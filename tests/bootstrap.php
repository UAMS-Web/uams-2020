<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/antecedent/patchwork/Patchwork.php';

if (! defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir().'/uams_2020-tests-no-wordpress/');
}

// ThemeBootstrap is called from tests/Pest.php so registration executes under coverage.
