<?php

declare(strict_types=1);

namespace Uams2020\Tests\Integration;

use Uams2020\Tests\Support\IntegrationTestCase;

// Run this file in its own command with WP_MULTISITE=1 UAMS_LIBRARY_SITE_CONTEXT=1.
if (getenv('UAMS_LIBRARY_SITE_CONTEXT') === '1') {
    require_once __DIR__.'/../Fixtures/integration-library-acf-marker.php';
}

require_once dirname(__DIR__, 2).'/lib/header.php';

final class LibraryMultisiteBehaviorTest extends IntegrationTestCase
{
    public function test_header_restores_the_current_site_and_blog_stack_after_reading_the_network_name(): void
    {
        if (! is_multisite() || getenv('UAMS_LIBRARY_SITE_CONTEXT') !== '1') {
            $this->markTestSkipped('Requires the separate multisite branding command.');
        }

        $blog = self::factory()->blog->create();
        $oldRootTitle = get_blog_option(1, 'blogname');
        update_blog_option(1, 'blogname', 'Network Name');
        switch_to_blog($blog);
        try {
            update_option('blogname', 'Current Name');
            update_option('options_uamswp_template', 'inside');
            update_option('options_uamswp_inside_subsite', 'fitness-center');
            $this->assertSame('inside', uams_get_site_info()['site']);
            $before = $GLOBALS['_wp_switched_stack'];
            ob_start();
            uamswp_site_image();
            $markup = ob_get_clean();
            $this->assertSame($blog, get_current_blog_id());
            $this->assertSame($before, $GLOBALS['_wp_switched_stack']);
            $this->assertSame('Current Name', get_bloginfo('name'));
            $this->assertStringContainsString('Network Name<span class="sr-only">:</span>', $markup);
            $this->assertStringContainsString('>Current Name</a>', $markup);
        } finally {
            restore_current_blog();
            update_blog_option(1, 'blogname', $oldRootTitle);
        }
    }
}
