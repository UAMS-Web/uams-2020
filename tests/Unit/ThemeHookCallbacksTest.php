<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\BlockRegistryDouble;
use Uams2020\Tests\Support\ThemeHooks;

use function Brain\Monkey\Actions\expectDone;
use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    $this->savedHookGet = $_GET;
    $_GET = [];
    if (! defined('DAY_IN_SECONDS')) {
        define('DAY_IN_SECONDS', 86400);
    }

    if (! class_exists('WP_Block_Type_Registry')) {
        class_alias(BlockRegistryDouble::class, 'WP_Block_Type_Registry');
    }

    BlockRegistryDouble::$registered = [];
});

afterEach(function (): void {
    $_GET = $this->savedHookGet;
    BlockRegistryDouble::$registered = [];
});

it('adds the ten-second schedule without replacing other schedules', function (): void {
    when('__')->returnArg();
    $callback = ThemeHooks::callback('filter', 'cron_schedules');
    expect($callback(['hourly' => ['interval' => 3600]]))->toBe([
        'hourly' => ['interval' => 3600], 'ten_seconds' => ['interval' => 10, 'display' => 'Every Ten Seconds'],
    ]);
});

/**
 * BreakToContinue is equivalent for a finite array queue: every later iteration skips
 * all work after the batch limit. Pending jobs and cached images stay the same; only
 * the extra iteration cost differs.
 */
it('processes at most twenty image jobs and persists only the remaining queue', function (int $count): void {
    $queue = [];
    for ($id = 1; $id <= $count; $id++) {
        $queue[$id * 2] = ['id' => $id, 'params' => ['width' => 100 + $id, 'height' => 50 + $id, 'hcrop' => 'left', 'vcrop' => 'top'], 'transient_key' => 'image-'.$id];
    }

    Functions\expect('get_option')->once()->with('image_sizer_queue', [])->andReturn($queue);
    $processed = min(20, $count);
    for ($id = 1; $id <= $processed; $id++) {
        Functions\expect('bis_get_attachment_image_src')->once()->with($id, [100 + $id, 50 + $id], ['left', 'top'])->andReturn(['src' => 'url-'.$id]);
        Functions\expect('set_transient')->once()->with('image-'.$id, 'url-'.$id, 432000);
    }

    if ($count === 0) {
        Functions\expect('delete_option')->never();
        Functions\expect('wp_clear_scheduled_hook')->never();
        Functions\expect('update_option')->never();
    } elseif ($count <= 20) {
        Functions\expect('delete_option')->once()->with('image_sizer_queue');
        Functions\expect('wp_clear_scheduled_hook')->once()->with('process_image_sizer_queue');
        Functions\expect('update_option')->never();
    } else {
        Functions\expect('update_option')->once()->with('image_sizer_queue', array_values(array_slice($queue, 20, null, true)), false);
        Functions\expect('delete_option')->never();
        Functions\expect('wp_clear_scheduled_hook')->never();
    }

    ThemeHooks::callback('action', 'process_image_sizer_queue')();
})->with([0, 1, 19, 20, 21]);

it('clears queued image work only when switching away from this theme', function ($oldName): void {
    Functions\expect('wp_clear_scheduled_hook')->times($oldName === 'uams-2020' ? 1 : 0)->with('process_image_sizer_queue');
    Functions\expect('delete_option')->times($oldName === 'uams-2020' ? 1 : 0)->with('image_sizer_queue');
    $old = $oldName === null ? null : new class($oldName)
    {
        public function __construct(private string $name) {}

        public function get_stylesheet(): string
        {
            return $this->name;
        }
    };
    ThemeHooks::callback('action', 'switch_theme')('new', null, $old);
})->with([[null], ['uams-2020'], ['another-theme']]);

it('limits manual queue requests to the admin capability and checks the nonce first', function (bool $requested, bool $canManage): void {
    if ($requested) {
        $_GET['process_image_queue'] = '1';
    }

    Functions\expect('current_user_can')->times($requested ? 1 : 0)->with('manage_options')->andReturn($canManage);
    expectDone('process_image_sizer_queue')->never();
    if ($requested && $canManage) {
        Functions\expect('check_admin_referer')->once()->with('process_image_queue_nonce')->andThrow(new RuntimeException('Rejected nonce'));
        expect(ThemeHooks::callback('action', 'admin_init'))->toThrow(RuntimeException::class, 'Rejected nonce');
    } else {
        Functions\expect('check_admin_referer')->never();
        ThemeHooks::callback('action', 'admin_init')();
        expect(true)->toBeTrue();
    }

    Functions\expect('wp_redirect')->never();
})->with([[false, false], [false, true], [true, false], [true, true]]);

it('shows queue notices only on the media screen to administrators', function (string $screen, bool $canManage, int $jobs, bool $processed): void {
    when('get_current_screen')->justReturn((object) ['id' => $screen]);
    when('current_user_can')->justReturn($canManage);
    when('get_option')->justReturn(array_fill(0, $jobs, []));
    when('admin_url')->alias(static fn ($path): string => 'https://example.test/wp-admin/'.$path);
    when('wp_nonce_url')->alias(static fn ($url, $nonce): string => $url.'&nonce='.$nonce);
    when('esc_url')->alias(htmlspecialchars(...));
    if ($processed) {
        $_GET['queue_processed'] = '0';
    }

    ob_start();
    ThemeHooks::callback('action', 'admin_notices')();
    $html = ob_get_clean();
    if ($screen !== 'upload' || ! $canManage) {
        expect($html)->toBe('');
    } else {
        expect(str_contains($html, 'Image resizing queue has '.$jobs.' pending jobs.'))->toBe($jobs > 0)
            ->and(str_contains($html, 'Image queue processed successfully.'))->toBe($processed);
        if ($jobs > 0) {
            expect($html)->toContain('href="https://example.test/wp-admin/upload.php?process_image_queue=1&amp;nonce=process_image_queue_nonce"');
        }
    }
})->with([['dashboard', true, 2, true], ['upload', false, 2, true], ['upload', true, 0, false], ['upload', true, 2, false], ['upload', true, 0, true], ['upload', true, 2, true]]);

it('preserves the block list outside marketing and permits the current marketing list by role', function (bool $hasPost, string $template, bool $admin): void {
    $post = (object) ['ID' => 42];
    $context = $hasPost ? (object) ['post' => $post] : (object) [];
    when('get_page_template_slug')->justReturn($template);
    when('current_user_can')->justReturn($admin);
    when('uamswp_section_allowed_blocks')->justReturn(['acf/uams-section', 'core/paragraph']);

    $callback = ThemeHooks::callback('filter', 'allowed_block_types_all', 110);
    $result = $callback(['existing'], $context);
    if (! $hasPost || $template !== 'templates/marketing.php') {
        expect($result)->toBe(['existing']);
    } else {
        $expected = ['acf/action-bar', 'acf/call-out', 'acf/cta', 'acf/link-list', 'acf/uams-news', 'acf/text-overlay', 'acf/image-side', 'acf/text-stacked', 'acf/livewhale-calendar', 'acf/uams-gallery', 'acf/uams-section', 'acf/uams-content', 'acf/fad-providers', 'acf/fad-locations', 'acf/logo-list'];
        if ($admin) {
            $expected[] = 'acf/uams-iframe';
        }

        $expected[] = 'core/paragraph';
        expect($result)->toBe($expected);
    }
})->with([[false, '', false], [true, 'other.php', true], [true, 'templates/marketing.php', false], [true, 'templates/marketing.php', true]]);

/**
 * RemoveArrayItem on the quote-bearing name is equivalent: WordPress 7.1.3 rejects
 * that name before registration, so it cannot be a key in the real registry.
 */
it('pins the entire global block removal list and the iframe capability gate', function (bool $admin): void {
    $blocked = json_decode(file_get_contents(__DIR__.'/../Fixtures/blocked-core-blocks.json'), true, flags: JSON_THROW_ON_ERROR);
    $blocked = array_values(array_diff($blocked, ['core/query-pagination-next"']));

    $retained = ['core/paragraph', 'plugin/custom', 'core/query-pagination-next', 'acf/uams-iframe'];
    BlockRegistryDouble::$registered = array_fill_keys([...$blocked, ...$retained], (object) []);
    when('current_user_can')->justReturn($admin);
    expect(ThemeHooks::callback('filter', 'allowed_block_types_all', 100)(false, null))->toBe($admin ? $retained : array_slice($retained, 0, 3));
})->with([true, false]);

it('pins optional block unregistration including the existing duplicate', function (bool $plugins): void {
    $registered = ['wpseopress/sitemap', 'searchwp/search-form', 'minervakb/faq', 'ajax-search-pro/block-asp-main', 'formidable/simple-form'];
    BlockRegistryDouble::$registered = $plugins ? array_fill_keys($registered, (object) []) : [];

    $removed = [];
    when('unregister_block_type')->alias(static function ($name) use (&$removed): void {
        $removed[] = $name;
    });
    ThemeHooks::callback('action', 'init', PHP_INT_MAX)();
    expect($removed)->toBe($plugins ? [
        'wpseopress/sitemap', 'wpseopress/local-business-field', 'wpseopress/local-business', 'wpseopress/breadcrumbs', 'wpseopress/how-to-step', 'wpseopress/how-to', 'wpseopress/table-of-contents', 'wpseopress/how-to-step',
        'searchwp/search-form', 'minervakb/tip', 'minervakb/info', 'minervakb/warning', 'minervakb/related', 'minervakb/article-content', 'minervakb/guestpost', 'ajax-search-pro/block-asp-main', 'frm-charts/graph', 'formidable/calculator',
    ] : []);
})->with([true, false]);

it('dispatches an authorized queue request before redirecting to the media library', function (): void {
    $_GET['process_image_queue'] = '1';
    Functions\expect('current_user_can')->once()->with('manage_options')->andReturn(true);
    Functions\expect('check_admin_referer')->once()->with('process_image_queue_nonce')->globally()->ordered()->andReturn(1);
    expectDone('process_image_sizer_queue')->once()->globally()->ordered();
    Functions\expect('admin_url')->once()->with('upload.php?queue_processed=1')->andReturn('https://example.test/wp-admin/upload.php?queue_processed=1');
    Functions\expect('wp_redirect')->once()->with('https://example.test/wp-admin/upload.php?queue_processed=1')->globally()->ordered()->andReturn(true);
    Functions\expect('exit')->once()->withNoArgs()->globally()->ordered()->andThrow(new RuntimeException('Exit reached'));
    expect(ThemeHooks::callback('action', 'admin_init'))->toThrow(RuntimeException::class, 'Exit reached');
});

it('maps both administrator and editor redirect capabilities to edit_pages', function (): void {
    $callback = ThemeHooks::callback('filter', 'srm_map_meta_caps');
    expect($callback('manage_options'))->toBe('edit_pages')
        ->and($callback('edit_pages'))->toBe('edit_pages');
});
