<?php

/**
 * First unit test pinning harness load for UAMS 2020.
 */

declare(strict_types=1);

it('loads the theme surface', function (): void {
    expect(dirname(__DIR__, 2).'/functions.php')->toBeFile();
});
