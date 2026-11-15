<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\OptionString;
use Uams2020\Tests\Support\ThemeHooks;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

beforeEach(function (): void {
    $this->savedMalformedGet = $_GET;
    $_GET = [];
});

afterEach(function (): void {
    $_GET = $this->savedMalformedGet;
});

it('treats image size names that are not an array as empty', function (mixed $sizes): void {
    when('__')->returnArg();
    expect(array_keys(uams_custom_add_image_size_names($sizes)))->toBe(['content-image-side', 'content-image-center', 'content-image-wide', 'content-image-full']);
})->with([[false], [''], [null], ['text'], [7]]);

it('treats header attributes that are not an array as empty and an aria label that is not text as empty', function (): void {
    expect(uamswp_add_aria(false))->toBe(['aria-label' => 'Site Header'])
        ->and(uamswp_add_aria('text'))->toBe(['aria-label' => 'Site Header'])
        ->and(uamswp_add_aria(['aria-label' => ['x']]))->toBe(['aria-label' => 'Site Header'])
        ->and(uamswp_add_aria(['aria-label' => 7]))->toBe(['aria-label' => '7Site Header']);
});

it('prints a skip link label that is not text as empty and ignores a footer setting that is not an array', function (): void {
    when('genesis_a11y')->justReturn(true);
    Functions\expect('genesis_skiplinks_markup')->once();
    when('genesis_nav_menu_supported')->justReturn(false);
    when('has_nav_menu')->justReturn(false);
    when('genesis_site_layout')->justReturn('full-width-content');
    when('current_theme_supports')->justReturn(true);
    when('get_theme_support')->justReturn('text');
    when('is_active_sidebar')->justReturn(true);
    when('esc_html__')->alias(static fn ($text) => $text === 'Skip to main content' ? ['array'] : $text);
    when('esc_url')->returnArg();
    ob_start();
    uamswp_skip_links();
    expect(ob_get_clean())->toBe('<nav aria-label="Skip links"><ul class="genesis-skip-link"><li><a href="#genesis-content" class="screen-reader-shortcut"> </a></li></ul></nav>');
});

it('names the main site only from an object with a text name', function (mixed $site, string $expected): void {
    when('get_blog_details')->justReturn($site);
    expect(uams_main_site_name())->toBe($expected);
})->with([
    [false, ''], [null, ''], ['text', ''], [7, ''],
    [new stdClass, ''], [(object) ['blogname' => ['x']], ''], [(object) ['blogname' => 'UAMS'], 'UAMS'],
]);

it('puts the main site name and a site title that is not text into the breadcrumb home link', function (): void {
    when('uams_get_site_info')->justReturn(['site' => 'uams', 'subsite' => 'clinic', 'department' => 'dept']);
    when('is_front_page')->justReturn(false);
    when('uams_get_home_link')->justReturn('https://home.test');
    when('network_home_url')->justReturn('https://network.test/');
    when('home_url')->justReturn('https://site.test');
    when('uams_site_title')->justReturn(['x']);
    when('get_blog_details')->justReturn((object) ['blogname' => 'Main']);
    $html = uams_breadcrumb_home_icon('crumb');
    expect($html)->toContain('title="Main"><span itemprop="name">Main</span>')
        ->and($html)->toContain('title=""><span itemprop="name"></span>');
});

it('starts the page crumbs from an empty list when the filter passes a value that is not an array', function (mixed $crumbs): void {
    when('uams_site_title')->justReturn('Site');
    when('uams_get_site_info')->justReturn(['site' => 'inside', 'subsite' => 'main', 'department' => '']);
    when('uams_get_home_link')->justReturn('https://inside.test');
    expect(sp_pro_breadcrumbs_crumbs($crumbs))->toBe([['<span class="fas fa-home"></span><span class="sr-only">Inside UAMS</span>', 'https://inside.test/']]);
})->with([[false], [''], [null], ['text']]);

it('names the site as the first crumb even when that crumb is not an array', function (): void {
    when('uams_site_title')->justReturn('Site');
    when('uams_get_site_info')->justReturn(['site' => 'other', 'subsite' => 'clinic', 'department' => 'main']);
    $result = sp_pro_breadcrumbs_crumbs(['text', ['Page', 'https://page.test']]);
    expect($result[0][0])->toBe('<span class="fas fa-home"></span><span class="sr-only">University of Arkansas for Medical Sciences</span>')
        ->and($result[1])->toBe([0 => 'Site'])
        ->and($result[2])->toBe(['Page', 'https://page.test']);
});

it('looks up the permalink of a missing current post', function (): void {
    Functions\expect('get_the_id')->once()->andReturn(false);
    Functions\expect('get_post_meta')->once()->with(0, 'post_custom_link', true)->andReturn('');
    Functions\expect('get_post_format')->once()->with(0)->andReturn(false);
    Functions\expect('get_permalink')->once()->with(0)->andReturn('https://example.test/current');
    expect(uams_get_permalink())->toBe('https://example.test/current');
});

it('looks up the permalink of a post that cannot be read', function (): void {
    when('get_post')->justReturn(null);
    Functions\expect('get_post_meta')->once()->with(0, 'post_custom_link', true)->andReturn('');
    Functions\expect('get_post_format')->once()->with(0)->andReturn('link');
    Functions\expect('get_permalink')->once()->with(0)->andReturn('https://example.test/none');
    expect(uams_get_permalink(55))->toBe('https://example.test/none');
});

it('compares a prefix of values that are not text without raising an error', function (): void {
    expect(startsWith(['x'], ['y']))->toBeFalse()
        ->and(startsWith(['x'], 'a'))->toBeFalse()
        ->and(startsWith(12345, '12'))->toBeTrue();
});

it('leaves a telephone number that is not text alone and dispatches an unsupported style unchanged', function (mixed $phone): void {
    expect(format_phone_us($phone))->toBe('')->and(format_phone_dash($phone))->toBe('')
        ->and(format_phone(['x'], '5015551234'))->toBe('5015551234')
        ->and(format_phone(new OptionString('us'), '5015551234'))->toBe('(501) 555-1234');
})->with([[false], [null], [5015551234], [['5', '0', '1', '5']]]);

it('formats the time of a date that is not text as the epoch', function (): void {
    expect(apStyleDate(['x']))->toBe(apStyleDate(''));
});

it('treats widget parameters that are not an array or have no markup as empty', function (): void {
    expect(footer_widgets(false))->toBe([])
        ->and(footer_widgets('text'))->toBe([])
        ->and(footer_widgets([false]))->toBe([false])
        ->and(footer_widgets([['id' => 'footer-1']]))->toBe([['id' => 'footer-1', 'before_widget' => '']])
        ->and(footer_widgets([['id' => 'footer-1', 'before_widget' => ['x']]]))->toBe([['id' => 'footer-1', 'before_widget' => '']]);
});

it('rejects a REST page size that is not text', function (): void {
    expect(rest_api_filter_add_filter_param(['kept' => 1], ['filter' => ['posts_per_page' => ['5']]]))->toBe(['kept' => 1]);
});

it('rejects a GTM container that is not text', function (mixed $value): void {
    expect(uamswp_validate_gtm_id(true, $value))->toBe('Enter a valid Google Tag Manager container ID (GTM-XXXXXXX).');
})->with([[['GTM-ABC']], [false], [7]]);

it('prints a GTM container that is not text as the empty container', function (): void {
    when('get_option')->alias(static fn ($name) => $name === 'options_google_tag_manager_id' ? ['x'] : '');
    when('esc_url')->returnArg();
    when('esc_js')->returnArg();
    ob_start();
    uamswp_gtm_1();
    uamswp_gtm_2();
    expect(ob_get_clean())->not->toContain('Array');
});

it('ignores an editor context that is not an object and section blocks that are not text', function (): void {
    when('get_page_template_slug')->justReturn('templates/marketing.php');
    when('current_user_can')->justReturn(false);
    when('uamswp_section_allowed_blocks')->justReturn(['acf/uams-section', ['x'], 7, 'core/paragraph']);
    $callback = ThemeHooks::callback('filter', 'allowed_block_types_all', 110);
    expect($callback(['existing'], false))->toBe(['existing'])
        ->and($callback(['existing'], 'text'))->toBe(['existing'])
        ->and($callback(['existing'], (object) ['post' => 5]))->toContain('acf/uams-section', 'core/paragraph')
        ->and($callback(['existing'], (object) ['post' => 5]))->not->toContain('', 7, '7');
    when('uamswp_section_allowed_blocks')->justReturn('text');
    expect($callback(['existing'], (object) ['post' => 5]))->not->toContain('text');
});

it('treats a geo field that is not an array as empty', function (mixed $field): void {
    when('function_exists')->alias(static fn ($name) => $name === 'geoip_detect2_get_external_ip_adress' ? true : relay());
    expect(uamswp_set_geo($field))->toBe([]);
})->with([[false], [null], ['text']]);

it('prints a reset key, login and site name that are not text as empty', function (): void {
    when('get_option')->justReturn(['x']);
    when('wp_specialchars_decode')->returnArg();
    when('__')->returnArg();
    Functions\expect('network_site_url')->once()->with('/uams-login/?action=rp&key=&login=', 'login')->andReturn('https://example.test/login');
    $message = my_retrieve_password_message('old', ['key'], ['login'], null);
    expect($message)->toContain("Site Name: \r\n")->and($message)->toContain("Username: \r\n");
});

it('shows no queue notice when the current screen cannot be read', function (mixed $screen): void {
    when('get_current_screen')->justReturn($screen);
    when('current_user_can')->justReturn(true);
    Functions\expect('get_option')->never();
    ob_start();
    ThemeHooks::callback('action', 'admin_notices')();
    expect(ob_get_clean())->toBe('');
})->with([[false], [null]]);

it('counts no queued image jobs when the queue option is not an array', function (): void {
    when('get_current_screen')->justReturn((object) ['id' => 'upload']);
    when('current_user_can')->justReturn(true);
    when('get_option')->justReturn('text');
    ob_start();
    ThemeHooks::callback('action', 'admin_notices')();
    expect(ob_get_clean())->toBe('');
});

it('adds the ten-second schedule when the schedule list is not an array', function (): void {
    when('__')->returnArg();
    expect(ThemeHooks::callback('filter', 'cron_schedules')(false))->toBe(['ten_seconds' => ['interval' => 10, 'display' => 'Every Ten Seconds']]);
});

it('does not resize with a cached value that is not text', function (): void {
    when('function_exists')->alias(static fn ($function) => $function === 'bis_get_attachment_image' ? true : relay());
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', 400, 200]);
    when('get_transient')->justReturn(['x']);
    expect(image_sizer(42, 100, 50))->toBeNull();
});

it('treats a queue, a queued job and its fields that are not arrays as empty', function (): void {
    when('function_exists')->alias(static fn ($function) => $function === 'bis_get_attachment_image' ? true : relay());
    when('time')->justReturn(1000);
    when('wp_get_attachment_image_src')->justReturn(['original.jpg', 400, 200]);
    when('get_transient')->justReturn(false);
    when('get_option')->alias(static fn ($name) => $name === 'image_sizer_queue' ? ['text', false, ['id' => ['x'], 'params' => 'text']] : relay());
    Functions\expect('update_option')->once()->withArgs(static fn ($name, $queue): bool => $name === 'image_sizer_queue' && count($queue) === 4);
    when('wp_next_scheduled')->justReturn(true);
    when('apply_filters')->alias(static fn ($hook, $value) => $value);
    when('wp_get_attachment_image_src')->alias(static fn ($id, $size = 'full') => ['fallback-'.(is_string($size) ? $size : 'array').'.jpg', 400, 200]);
    expect(image_sizer(42, 100, 50))->toBe('fallback-full.jpg');
});

it('reads fallback sizes and image URLs that are not text as empty', function (): void {
    when('apply_filters')->alias(static fn ($hook, $value) => ['x']);
    when('wp_get_attachment_image_src')->alias(static fn ($id, $size) => ['image-'.$size.'.jpg']);
    expect(image_sizer_get_fallback(42, ['size']))->toBe('image-.jpg');
    when('wp_get_attachment_image_src')->justReturn([['x']]);
    expect(image_sizer_get_fallback(42, 'full'))->toBe('');
    when('wp_get_attachment_image_src')->justReturn(false);
    expect(image_sizer_get_fallback(42, 'full'))->toBe('');
});

it('drops queued jobs that are not arrays and caches an empty URL for a job without one', function (): void {
    when('get_option')->justReturn(['text', ['id' => 7, 'params' => 'text', 'transient_key' => ['x']]]);
    Functions\expect('bis_get_attachment_image_src')->once()->with(7, [null, null], [null, null])->andReturn(false);
    Functions\expect('set_transient')->once()->with('', null, 432000);
    Functions\expect('update_option')->never();
    Functions\expect('delete_option')->once()->with('image_sizer_queue');
    when('wp_clear_scheduled_hook')->justReturn(null);
    if (! defined('DAY_IN_SECONDS')) {
        define('DAY_IN_SECONDS', 86400);
    }

    ThemeHooks::callback('action', 'process_image_sizer_queue')();
});
