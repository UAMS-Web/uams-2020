<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\LibraryBrandingScenario;
use Uams2020\Tests\Support\LibraryPageScenario;
use Uams2020\Tests\Support\ModuleTemplateCapture;
use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Functions\when;

it('pins entry-header attributes with both cover-image states', function (bool $image): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_field')->once()->with('page_cover_image', 41)->andReturn($image);
    Functions\expect('get_field')->once()->with('page_landing_page_cover_image', 41)->andReturn($image);
    $base = ['class' => 'original', 'id' => 'keep'];
    expect(uamswp_attributes_entry_header($base))->toBe(['class' => 'original sr-only', 'id' => 'keep']);
    expect(uamswp_attributes_entry_header_graphic_title($base))->toBe(['class' => 'original uams-module extra-padding graphic-title'.($image ? ' bg-image' : '').' bg-red', 'id' => 'keep']);
    expect(uamswp_attributes_entry_header_landing_page_title($base))->toBe(['class' => 'original uams-module extra-padding landing-page-title'.($image ? ' bg-image' : ''), 'id' => 'keep']);
})->with([true, false]);

it('adds entry-header classes to attributes that are missing or malformed', function ($attributes, array $expected): void {
    expect(uamswp_attributes_entry_header($attributes))->toBe($expected);
})->with([[null, ['class' => ' sr-only']], [false, ['class' => ' sr-only']], [['id' => 'x'], ['id' => 'x', 'class' => ' sr-only']], [['class' => ['a']], ['class' => ' sr-only']]]);

it('treats unexpected cover-image values as no image', function ($image): void {
    when('get_the_id')->justReturn(41);
    when('get_field')->justReturn($image);
    expect(uamswp_attributes_entry_header_graphic_title(['class' => 'a']))->toBe(['class' => 'a uams-module extra-padding graphic-title bg-red'])
        ->and(uamswp_attributes_entry_header_landing_page_title(['class' => 'a']))->toBe(['class' => 'a uams-module extra-padding landing-page-title']);
})->with([[false], [''], [null], [0], ['0'], [['id' => 5]]]);

it('treats cover-image ids given as numeric strings as images', function (): void {
    when('get_the_id')->justReturn(41);
    when('get_field')->justReturn('77');
    expect(uamswp_attributes_entry_header_graphic_title(['class' => 'a']))->toBe(['class' => 'a uams-module extra-padding graphic-title bg-image bg-red']);
});

it('prints nothing for lead paragraphs and headings that are not text', function (string $function, $value, string $expected): void {
    when('get_the_id')->justReturn(41);
    when('get_the_ID')->justReturn(41);
    when('get_the_title')->justReturn('Fallback Title');
    when('get_field')->justReturn($value);
    ob_start();
    $function();
    expect(ob_get_clean())->toBe($expected);
})->with([
    ['uamswp_graphic_title_lead_paragraph', ['x'], ''],
    ['uamswp_landing_page_title_lead_paragraph', null, ''],
    ['uamswp_landing_page_title_do_post_title', ['x'], '<h1 class="entry-title" itemprop="headline">Fallback Title</h1>'],
]);

it('passes no hero rows when the hero field is not an array', function ($field): void {
    ModuleTemplateCapture::$calls = [];
    when('get_the_id')->justReturn(41);
    when('get_field')->justReturn($field);
    Functions\expect('get_stylesheet_directory')->once()->andReturn(__DIR__.'/../Fixtures/library-theme');
    ob_start();
    uamswp_page_hero();
    ob_end_clean();
    expect(ModuleTemplateCapture::$calls[0]['variables'])->toBe(['hero_rows' => null, 'i' => 0]);
})->with([[false], [null], ['text']]);

it('treats a failed page lookup as having no child pages', function (): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_post_meta')->once()->andReturn(false);
    Functions\expect('get_pages')->once()->with(['child_of' => 41])->andReturn(false);
    Functions\expect('uamswp_list_child_posts')->never();
    uamswp_list_child_pages();
});

it('falls back to post id zero when the current post is unknown', function (): void {
    when('get_the_id')->justReturn(false);
    when('get_the_ID')->justReturn(false);
    Functions\expect('get_post_meta')->once()->with(0, 'page_hide_breadcrumbs', true)->andReturn('');
    expect(uamswp_hide_breadcrumbs())->toBeFalse();
    Functions\expect('get_the_title')->once()->with(0)->andReturn('T');
    Functions\expect('get_the_permalink')->once()->with(0)->andReturn('/p');
    Functions\expect('is_archive')->once()->andReturn(false);
    expect(uamswp_entry_title_h1('ignored'))->toBe('<h1 class="entry-title" itemprop="headline">T</h1>');
});

it('pins the exact wrappers around graphic and landing-page headings', function (string $function, string $expected): void {
    ob_start();
    $function();
    expect(ob_get_clean())->toBe($expected);
})->with([
    ['uamswp_graphic_title_wrap_open', '<div class="col-12">'], ['uamswp_graphic_title_inner_2', '</div>'],
    ['uamswp_graphic_title_inner_3', '</div>'], ['uamswp_graphic_title_wrap_close', '</div>'],
    ['uamswp_landing_page_title_wrap_open', '<div class="col-12">'], ['uamswp_landing_page_title_inner_2', '</div>'],
    ['uamswp_landing_page_title_inner_3', '</div>'], ['uamswp_landing_page_title_wrap_close', '</div>'],
]);

it('pins optional lead paragraphs without changing authored markup', function (string $function, string $field, string $class, $body): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_field')->once()->with($field, 41)->andReturn($body);
    ob_start();
    $function();
    expect(ob_get_clean())->toBe($body ? '<div class="'.$class.'"><p>'.$body.'</p></div>' : '');
})->with([
    ['uamswp_graphic_title_lead_paragraph', 'page_description', 'graphic-title-body', '<em>Lead</em>'],
    ['uamswp_graphic_title_lead_paragraph', 'page_description', 'graphic-title-body', false],
    ['uamswp_landing_page_title_lead_paragraph', 'page_landing_page_description', 'landing-page-title-body', '<em>Lead</em>'],
    ['uamswp_landing_page_title_lead_paragraph', 'page_landing_page_description', 'landing-page-title-body', ''],
]);

it('uses an explicit landing-page heading before falling back to the post title', function ($heading, string $expected): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_field')->once()->with('page_landing_page_heading', 41)->andReturn($heading);
    Functions\expect('get_the_title')->times(empty($heading) ? 1 : 0)->withNoArgs()->andReturn('Fallback Title');
    ob_start();
    uamswp_landing_page_title_do_post_title();
    expect(ob_get_clean())->toBe('<h1 class="entry-title" itemprop="headline">'.$expected.'</h1>');
})->with([['<em>Heading</em>', '<em>Heading</em>'], [false, 'Fallback Title'], ['0', 'Fallback Title']]);

it('passes hero rows and the initial index to the hero template', function (): void {
    ModuleTemplateCapture::$calls = [];
    when('get_the_id')->justReturn(41);
    Functions\expect('get_field')->once()->with('page_hero', 41)->andReturn(['hero' => [['slide' => 'one']]]);
    Functions\expect('get_stylesheet_directory')->once()->andReturn(__DIR__.'/../Fixtures/library-theme');
    ob_start();
    uamswp_page_hero();
    expect(ob_get_clean())->toBe('<div class="col-12">[template:hero.php]</div>')->and(ModuleTemplateCapture::$calls)->toBe([
        ['template' => 'hero.php', 'variables' => ['hero_rows' => [['slide' => 'one']], 'i' => 0]],
    ]);
});

it('pins breadcrumb suppression by current-post metadata', function ($hidden, $expected): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_post_meta')->twice()->with(41, 'page_hide_breadcrumbs', true)->andReturn($hidden);
    expect(uamswp_hide_breadcrumbs())->toBe($expected);
    expectRemoved('genesis_after_header')->times($hidden ? 1 : 0)->with('genesis_do_breadcrumbs');
    expectRemoved('genesis_after_header')->times($hidden ? 1 : 0)->with('sp_breadcrumb_after_header');
    remove_breadcrumbs();
})->with([[true, 41], [false, false], ['0', false]]);

it('pins linked archive headings and plain single-page headings', function (bool $archive): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_the_title')->once()->with(41)->andReturn('Post Title');
    Functions\expect('get_the_permalink')->once()->with(41)->andReturn('/post');
    Functions\expect('is_archive')->once()->andReturn($archive);
    expect(uamswp_entry_title_h1('ignored'))->toBe($archive ? '<h2 class="entry-title" itemprop="headline"><a href="/post">Post Title</a></h2>' : '<h1 class="entry-title" itemprop="headline">Post Title</h1>');
})->with([true, false]);

it('pins post bylines only on single posts', function (bool $single, string $type, bool $hidden, ?string $expected): void {
    Functions\expect('is_single')->once()->andReturn($single);
    Functions\expect('get_post_type')->times($single ? 1 : 0)->andReturn($type);
    Functions\expect('get_field')->times($single && $type === 'post' ? 1 : 0)->with('post_hide_author')->andReturn($hidden);
    expect(uamswp_post_info_filter('ignored'))->toBe($expected);
})->with([
    [false, 'post', false, null], [true, 'page', false, null],
    [true, 'post', true, 'Posted on [post_date]'], [true, 'post', false, 'Posted by [post_author_posts_link] on [post_date]'],
]);

it('lists child pages only when visible, present and outside search', function (bool $hidden, array $pages, bool $search, bool $display): void {
    when('get_the_id')->justReturn(41);
    Functions\expect('get_post_meta')->once()->with(41, 'page_hide_child_menu', true)->andReturn($hidden);
    Functions\expect('get_pages')->times($hidden ? 0 : 1)->with(['child_of' => 41])->andReturn($pages);
    Functions\expect('is_search')->times(! $hidden && count($pages) > 0 ? 1 : 0)->andReturn($search);
    Functions\expect('uamswp_list_child_posts')->times($display ? 1 : 0)->with('page', 'Subpages')->andReturnUsing(static function (): void {
        echo 'CHILDREN';
    });
    ob_start();
    $result = uamswp_list_child_pages();
    expect(ob_get_clean())->toBe($display ? 'CHILDREN' : '')->and($result)->toBeNull();
})->with([[true, [1], false, false], [false, [], false, false], [false, [1], true, false], [false, [1], false, true]]);

it('pins every title-option hook, callback, priority and accepted-argument count', function ($option, array $expected): void {
    expect(LibraryPageScenario::options($option))->toBe($expected);
})->with(array_map(static fn ($name, $expected): array => [json_decode($name, true, flags: JSON_THROW_ON_ERROR), $expected], array_keys(json_decode(file_get_contents(__DIR__.'/../Fixtures/library-page-option-hooks.json'), true, flags: JSON_THROW_ON_ERROR)), array_values(json_decode(file_get_contents(__DIR__.'/../Fixtures/library-page-option-hooks.json'), true, flags: JSON_THROW_ON_ERROR))));

it('pins all cover-image CSS sizes, mobile fallbacks and missing-image markup', function (string $function, array $fields, bool $resizer, string $expected): void {
    LibraryPageScenario::configure($fields, $resizer);
    expect(LibraryBrandingScenario::render($function))->toBe($expected);
})->with(json_decode(file_get_contents(__DIR__.'/../Fixtures/library-page-image-output.json'), true, flags: JSON_THROW_ON_ERROR));

$stringablePageOptions = [];
foreach (json_decode(file_get_contents(__DIR__.'/../Fixtures/library-page-option-hooks.json'), true, flags: JSON_THROW_ON_ERROR) as $name => $expected) {
    $option = json_decode($name, true, flags: JSON_THROW_ON_ERROR);
    if (is_string($option)) {
        $stringablePageOptions[] = [new OptionString($option), $expected];
    }
}

it('accepts stringable ACF title-option values without changing hook selection', function ($option, array $expected): void {
    expect(LibraryPageScenario::options($option))->toBe($expected);
})->with($stringablePageOptions);

it('keeps stringable raw post types supplied by WordPress extensions', function (): void {
    when('is_single')->justReturn(true);
    when('get_post_type')->justReturn(new OptionString('post'));
    when('get_field')->justReturn(false);

    expect(uamswp_post_info_filter('old'))->toBe('Posted by [post_author_posts_link] on [post_date]');
});
