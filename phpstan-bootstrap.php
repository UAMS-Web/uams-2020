<?php

declare(strict_types=1);

/**
 * Stand-ins for constants PHPStan cannot discover.
 *
 * CHILD_DIR and CHILD_URL are defined by the Genesis framework at runtime. The
 * UAMSWP_THEME_* constants are defined by `define()` inside a function in
 * functions.php, which PHPStan does not evaluate when discovering constants.
 */
define('CHILD_DIR', __DIR__);
define('CHILD_URL', 'https://example.test/theme');
define('UAMSWP_THEME_MODULES', __DIR__.'/lib/modules/');
define('UAMSWP_THEME_CSS', 'https://example.test/theme/assets/css/');
define('UAMSWP_THEME_JS', 'https://example.test/theme/assets/js/');
