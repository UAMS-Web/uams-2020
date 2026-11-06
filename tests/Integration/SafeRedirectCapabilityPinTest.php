<?php

/**
 * Pins the theme's current behavior around Safe Redirect Manager capabilities
 * and the block-list filter that the reconcile change (#647) appends beside.
 *
 * @see functions.php
 */

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Uams2020\Tests\Support\IntegrationTestCase;
use WP_Block_Editor_Context;

final class SafeRedirectCapabilityPinTest extends IntegrationTestCase
{
    public function test_currently_registers_no_srm_map_meta_caps_filter(): void
    {
        $this->assertFalse(has_filter('srm_map_meta_caps'));
    }

    public function test_currently_passes_the_srm_capability_through_unchanged(): void
    {
        $this->assertSame('manage_options', apply_filters('srm_map_meta_caps', 'manage_options'));
        $this->assertSame('edit_pages', apply_filters('srm_map_meta_caps', 'edit_pages'));
    }

    public function test_currently_removes_the_listed_core_blocks_from_the_allowed_blocks(): void
    {
        $allowed = apply_filters('allowed_block_types_all', true, new WP_Block_Editor_Context);

        $this->assertIsArray($allowed);
        $this->assertNotContains('core/latest-comments', $allowed);
        $this->assertNotContains('core/spacer', $allowed);
        $this->assertContains('core/paragraph', $allowed);
    }
}
