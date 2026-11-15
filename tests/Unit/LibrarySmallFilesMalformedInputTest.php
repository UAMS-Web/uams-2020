<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\ModuleTemplateCapture;

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;

it('looks up no thumbnail for a current post that is not an object', function (mixed $post): void {
    $old = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = $post;
    Functions\expect('get_post_thumbnail_id')->once()->withArgs(static fn ($id): bool => $id === 0)->andReturn(false);
    Functions\expect('wp_get_attachment_image_src')->once()->withArgs(static fn ($id, $size): bool => $id === 0 && $size === 'full')->andReturn(false);
    try {
        expect(uamswp_post_image())->toBe('');
    } finally {
        $GLOBALS['post'] = $old;
    }
})->with([[null], [false], ['text']]);

it('reads the post and thumbnail ids as integers and falls back to the first image', function (mixed $postId, mixed $thumbnail, int $expectedPost, int $expectedThumbnail): void {
    $old = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = (object) ['ID' => $postId, 'post_content' => '<img src="inline">'];
    Functions\expect('get_post_thumbnail_id')->once()->withArgs(static fn ($id): bool => $id === $expectedPost)->andReturn($thumbnail);
    Functions\expect('wp_get_attachment_image_src')->once()->withArgs(static fn ($id, $size): bool => $id === $expectedThumbnail && $size === 'full')->andReturn(false);
    try {
        expect(uamswp_post_image())->toBe('inline');
    } finally {
        $GLOBALS['post'] = $old;
    }
})->with([['92', '51', 92, 51], [92, false, 92, 0], [['x'], '', 0, 0], [null, 0, 0, 0]]);

it('finds no first image when the post or its content is not usable', function (mixed $post): void {
    $old = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = $post;
    try {
        expect(uamswp_get_first_image())->toBe('');
    } finally {
        $GLOBALS['post'] = $old;
    }
})->with([[null], [false], ['<img src="x">'], [new stdClass], [(object) ['post_content' => ['<img src="x">']]]]);

it('treats body classes that are not an array as empty', function (mixed $classes, bool $blog): void {
    Functions\expect('is_page_template')->once()->with('page_blog.php')->andReturn($blog);
    expect(uamswp_body_class($classes))->toBe($blog ? ['blog'] : []);
})->with([[false, true], [null, false], ['text', true]]);

it('treats header attributes and site info that are not text as empty', function (): void {
    when('uams_get_site_info')->justReturn(['site' => ['x'], 'subsite' => null]);
    expect(uamswp_site_add_css_attr(['class' => ['a'], 'id' => 'keep']))->toBe(['class' => '  ', 'id' => 'keep']);
    when('uams_get_site_info')->justReturn([]);
    expect(uamswp_site_add_css_attr(false))->toBe(['class' => '  ']);
    when('uams_get_site_info')->justReturn(['site' => 'institute', 'subsite' => 'eye']);
    expect(uamswp_site_add_css_attr(null))->toBe(['class' => ' institute eye']);
});

it('looks up the template of an editor id that is not text as page zero', function (): void {
    Functions\expect('get_page_template_slug')->once()->withArgs(static fn ($id): bool => $id === 0)->andReturn('');
    expect(uamswp_disable_editor(['5']))->toBeFalse();
});

it('keeps the classic editor when the current screen cannot be read', function (mixed $screen): void {
    $saved = $_GET;
    $_GET = ['post' => '5'];
    Functions\expect('get_current_screen')->once()->andReturn($screen);
    Functions\expect('get_page_template_slug')->never();
    Functions\expect('remove_post_type_support')->never();
    try {
        uamswp_disable_classic_editor();
    } finally {
        $_GET = $saved;
    }
})->with([[null], [false]]);

it('prints an embed title that is not text as empty and keeps the markup a string', function (mixed $title): void {
    when('esc_attr')->alias(static fn ($value): string => htmlspecialchars($value, ENT_QUOTES));
    $data = (object) ['title' => $title];
    expectApplied('uamswp_oembed_title')->once()->with('', '<iframe src="v"></iframe>', $data, 'https://video.test/7')->andReturn(['not text']);
    expect(filter_oembed_dataparse('<iframe src="v"></iframe>', $data, 'https://video.test/7'))->toBe('<iframe title="" src="v"></iframe>');
})->with([[['x']], [new stdClass]]);

it('prints the placeholder title as empty when the block title is not text', function (): void {
    when('__')->returnArg();
    when('esc_html')->returnArg();
    when('esc_html__')->returnArg();
    ob_start();
    $shown = uamswp_block_placeholder(['title' => ['x'], 'data' => []], true);
    $html = ob_get_clean();
    expect($shown)->toBeTrue()->and($html)->toContain('<strong></strong>');
});

it('rejects an iframe URL that is not text and ignores allowlist entries that are not text', function (): void {
    when('wp_parse_url')->alias(parse_url(...));
    when('apply_filters')->alias(static fn ($hook, $value) => [['x'], 'example.test']);
    expect(uamswp_iframe_url_is_allowed(['https://example.test']))->toBeFalse()
        ->and(uamswp_iframe_url_is_allowed('https://example.test/path'))->toBeTrue()
        ->and(uamswp_iframe_url_is_allowed('https://other.test/path'))->toBeFalse();
});

it('treats comment form defaults that are not an array as empty', function (mixed $args): void {
    when('_x')->returnArg();
    expect(array_keys(uamswp_comment_form_args($args)))->toBe(['comment_field', 'class_submit']);
})->with([[false], [null], ['text']]);

it('treats a request query that is not an array as empty', function (mixed $query): void {
    expect(remove_page_from_query_string($query))->toBe([]);
})->with([[false], [null], ['text']]);

it('treats allowed origins that are not an array as empty', function (mixed $origins): void {
    $actual = add_allowed_origins($origins);
    expect($actual)->toBeArray()->and($actual)->toContain('https://www.uams.edu');
})->with([[false], [null], ['text']]);

it('leaves plugin updates alone when the response list is not an array', function (mixed $response): void {
    $value = (object) ['response' => $response];
    expect(disable_plugin_updates($value))->toBe($value)->and($value->response)->toBe($response);
})->with([[null], [false], ['text']]);

it('renders widget wrappers and ids that are not text as empty', function (string $class, string $template, string $field): void {
    when('__')->returnArg();
    ModuleTemplateCapture::$calls = [];
    Functions\expect('get_field')->atLeast()->once()->withArgs(static fn ($name, $id): bool => $id === 'widget_')->andReturn(null);
    Functions\expect('get_stylesheet_directory')->once()->andReturn(__DIR__.'/../Fixtures/library-theme');
    ob_start();
    (new $class)->widget(['before_widget' => ['x'], 'after_widget' => false, 'widget_id' => ['y']], []);
    expect(ob_get_clean())->toBe('[template:'.$template.']')->and(ModuleTemplateCapture::$calls)->toHaveCount(1);
})->with([
    [UAMSWP_Call_Out_Widget::class, 'call-out.php', 'call_out_heading'],
    [UAMSWP_CTA_Widget::class, 'cta.php', 'cta_bar_heading'],
]);

it('treats a widget title that is not text as empty', function (string $class): void {
    when('__')->returnArg();
    Functions\expect('esc_attr')->once()->with('')->andReturn('ESCAPED');
    Functions\expect('_e')->once();
    ob_start();
    (new $class)->form(['title' => ['x']]);
    ob_end_clean();
    expect((new $class)->update(['title' => ['x']], []))->toBe(['title' => '']);
})->with([[UAMSWP_Call_Out_Widget::class], [UAMSWP_CTA_Widget::class]]);
