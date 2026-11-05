<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\BlockRegistryDouble;

use function Brain\Monkey\Functions\when;

it('pins image extraction including its greedy same-line match', function (string $content, string $expected): void {
    $old = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = (object) ['post_content' => $content];
    try {
        expect(uamswp_get_first_image())->toBe($expected);
    } finally {
        $GLOBALS['post'] = $old;
    }
})->with([
    ['text', ''], ['<img src="first"><img src="second">', 'second'], ["<img src=\"first\">\n<img src=\"second\">", 'first'], ["<IMG alt='x' src='chosen'>", 'chosen'], ['<img data-src="lazy">', 'lazy'],
]);

/**
 * lib/misc.php: EmptyStringToNotEmpty on the initial image value is equivalent. The attachment API
 * overwrites it before any read.
 */
it('uses a featured image before scanning the content', function ($image, string $expected): void {
    $old = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = (object) ['ID' => 92, 'post_content' => '<img src="first">'];
    Functions\expect('get_post_thumbnail_id')->once()->with(92)->andReturn(51);
    Functions\expect('wp_get_attachment_image_src')->once()->with(51, 'full')->andReturn([$image]);
    try {
        expect(uamswp_post_image())->toBe($expected);
    } finally {
        $GLOBALS['post'] = $old;
    }
})->with([['featured', 'featured'], ['', 'first']]);

it('pins home-featured hook conditions and always adds the body class filter', function (bool $front, bool $active): void {
    Functions\expect('is_front_page')->once()->andReturn($front);
    Functions\expect('is_active_sidebar')->times($front ? 1 : 0)->with('home-featured')->andReturn($active);
    uamswp_do_meta();
    expect(has_action('genesis_after_header', 'uamswp_do_home_featured'))->toBe($front && $active ? 10 : false);
    expect(has_filter('body_class', 'uamswp_body_class'))->toBe(10);
})->with([[true, true], [true, false], [false, true], [false, false]]);

it('pins the second blog class helper and site body classes', function (bool $blog): void {
    Functions\expect('is_page_template')->once()->with('page_blog.php')->andReturn($blog);
    expect(uamswp_body_class(['base']))->toBe($blog ? ['base', 'blog'] : ['base']);
    when('uams_get_site_info')->justReturn(['site' => 'institute', 'subsite' => 'eye']);
    expect(uamswp_site_add_css_attr(['class' => 'base', 'id' => 'keep']))->toBe(['class' => 'base institute eye', 'id' => 'keep']);
})->with([true, false]);

it('keeps the fixed block whitelist followed by nonblocked registered keys', function (): void {
    $blocked = ['core/latest-comments', 'core/archives', 'core/tag-cloud', 'core/search', 'core/rss', 'core/calendar', 'core/latest-posts', 'acf/acfb-starrating', 'acf/acfb-pricelist', 'acf/acfb-pricingbox', 'acf/acfb-multibuttons', 'acf/acfb-progressbar'];
    $saved = BlockRegistryDouble::$registered;
    BlockRegistryDouble::$registered = array_fill_keys([...$blocked, 'plugin/one', 'core/image'], new stdClass);
    $expected = ['core/image', 'core/paragraph', 'core/heading', 'core/list', 'core/quote', 'core/file', 'core/table', 'core/code', 'core/freeform', 'core/html', 'core/preformatted', 'core/pullquote', 'core/media-text', 'core/spacer', 'core/separator', 'core/embed', 'core-embed/twitter', 'core-embed/youtube', 'core-embed/facebook', 'core-embed/instagram', 'core-embed/flickr', 'core-embed/vimeo', 'core-embed/issuu', 'core-embed/screencast', 'core-embed/scribd', 'core-embed/slideshare', 'core-embed/ted', 'core/shortcode', 'plugin/one', 'core/image'];
    try {
        expect(uamswp_allowed_block_types(['ignored']))->toBe($expected);
    } finally {
        BlockRegistryDouble::$registered = $saved;
    }
});

it('disables the editor only for an existing module-template page', function ($id, $template, bool $expected): void {
    Functions\expect('get_page_template_slug')->times(empty($id) ? 0 : 1)->with((int) $id)->andReturn($template);
    expect(uamswp_disable_editor($id))->toBe($expected);
})->with([[false, '', false], [null, '', false], [0, '', false], ['37', 'templates/modules.php', true], [37, 'templates/marketing.php', false], [37, false, false]]);

it('pins the default editor ID', function (): void {
    Functions\expect('get_page_template_slug')->never();
    expect(uamswp_disable_editor())->toBeFalse();
});

it('gates Gutenberg removal on admin, post ID and module template', function (bool $admin, array $query, string $template, $canEdit, $expected): void {
    $old = $_GET;
    $_GET = $query;
    Functions\expect('is_admin')->once()->andReturn($admin);
    Functions\expect('get_page_template_slug')->times($admin && ! empty($query['post']) ? 1 : 0)->with((int) ($query['post'] ?? 0))->andReturn($template);
    try {
        expect(uamswp_disable_gutenberg($canEdit, 'page'))->toBe($expected);
    } finally {
        $_GET = $old;
    }
})->with([
    [false, ['post' => '37'], 'templates/modules.php', 'unchanged', 'unchanged'],
    [true, [], 'templates/modules.php', true, true], [true, ['post' => 0], 'templates/modules.php', true, true],
    [true, ['post' => '37'], 'templates/modules.php', true, false],
    [true, ['post' => '37'], 'other', false, false], [true, ['post' => '37'], 'other', true, true],
]);

it('removes classic editing only from the page screen with a module-template ID', function (string $screen, array $query, string $template, bool $remove): void {
    $old = $_GET;
    $_GET = $query;
    Functions\expect('get_current_screen')->once()->andReturn((object) ['id' => $screen]);
    $lookup = $screen === 'page' && isset($query['post']) && ! empty($query['post']);
    Functions\expect('get_page_template_slug')->times($lookup ? 1 : 0)->with((int) ($query['post'] ?? 0))->andReturn($template);
    Functions\expect('remove_post_type_support')->times($remove ? 1 : 0)->with('page', 'editor');
    try {
        uamswp_disable_classic_editor();
    } finally {
        $_GET = $old;
    }
})->with([
    ['post', ['post' => '37'], 'templates/modules.php', false], ['page', [], 'templates/modules.php', false],
    ['page', ['post' => 0], 'templates/modules.php', false], ['page', ['post' => '37'], 'templates/modules.php', true],
    ['page', ['post' => '37'], 'other', false],
]);
