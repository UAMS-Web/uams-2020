<?php

/**
 * First unit test pinning harness load for UAMS 2020.
 */

declare(strict_types=1);

namespace Uams2020\Tests\Unit;

use Uams2020\Tests\Support\UnitTestCase;

final class HarnessSmokeTest extends UnitTestCase
{
    /**
     * @return void
     */
    public function test_harness_loads_plugin_surface(): void
    {
        $this->assertFileExists(dirname(__DIR__, 2).'/functions.php');
        $this->assertTrue(true);
    }
}
