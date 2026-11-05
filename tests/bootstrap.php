<?php

declare(strict_types=1);

use Pest\Mutate\Support\StreamWrapper;

// PHPUnit's isolated processes need the same Composer loader when launched by Pest.
if (! defined('PHPUNIT_COMPOSER_INSTALL')) {
    define('PHPUNIT_COMPOSER_INSTALL', __DIR__.'/../vendor/autoload.php');
}

require_once PHPUNIT_COMPOSER_INSTALL;

// An isolated PHPUnit child does not boot Pest's mutation plugin itself.
$uamsMutationSource = getenv('PEST_MUTATION_TESTING');
if (function_exists('__phpunit_run_isolated_test') && $uamsMutationSource !== false) {
    StreamWrapper::start($uamsMutationSource, (string) getenv('PEST_MUTATION_FILE'));
}

require_once __DIR__.'/../vendor/antecedent/patchwork/Patchwork.php';

if (! defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir().'/uams_2020-tests-no-wordpress/');
}

// ThemeBootstrap is called from tests/Pest.php so registration executes under coverage.
