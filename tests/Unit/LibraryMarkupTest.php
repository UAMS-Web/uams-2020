<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\LibraryLoader;
use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Filters\expectAdded;
use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;

it('pins embed titles and the exact attributes stripped from provider markup', function (string $input, array $data, string $title, string $expected): void {
    Functions\expect('__')->zeroOrMoreTimes()->with('%s:', 'uamswp-network')->andReturn('%s:');
    when('esc_attr')->alias(static fn ($value): string => htmlspecialchars($value, ENT_QUOTES));
    $object = (object) $data;
    expectApplied('uamswp_oembed_title')->once()->with($title, $input, $object, 'https://video.test/7')->andReturn($title.' & filtered');
    expect(filter_oembed_dataparse($input, $object, 'https://video.test/7'))->toBe($expected);
})->with([
    ['<iframe frameborder="0" width="640" height="480" src="v"></iframe>', ['title' => 'Talk', 'type' => 'video'], 'Video: Talk', '<iframe title="Video: Talk &amp; filtered"  src="v"></iframe>'],
    ['<iframe title="Existing" src="v"></iframe>', ['title' => 'Ignored', 'type' => 'photo'], 'Existing', '<iframe title="Existing &amp; filtered" src="v"></iframe>'],
    ["<IFRAME TITLE='Clip' src='v'></IFRAME>", ['type' => 'video'], 'Video: Clip', '<IFRAME title="Video: Clip &amp; filtered" src=\'v\'></IFRAME>'],
    ['<iframe title="" src="v"></iframe>', ['title' => 'Fallback'], 'Fallback', '<iframe title="Fallback &amp; filtered" src="v"></iframe>'],
    ['<iframe src="v"></iframe>', [], '', '<iframe title=" &amp; filtered" src="v"></iframe>'],
    [' <iframe width="3" height="4" frameborder="1"></iframe>', ['title' => 'Outside', 'type' => 'video'], 'Video: Outside', ' <iframe frameborder="1"></iframe>'],
    ['<div title="Other"><iframe height="4"></iframe></div>', ['title' => 'Data', 'type' => 'audio'], 'Other', '<div title="Other &amp; filtered"><iframe height="4"></iframe></div>'],
    ['<iframe width="abc" height="5"></iframe>', ['title' => 'No type', 'type' => ''], 'No type', '<iframe title="No type &amp; filtered" width="abc" height="5"></iframe>'],
]);

it('preserves structural wrapper content at every boundary', function ($original, string $expected): void {
    expect(uamswp_filter_structural_wrap('BASE', $original))->toBe($expected);
})->with([['open', 'BASE<div class="row">'], ['close', '</div>BASE'], ['other', 'BASE'], ['', 'BASE']]);

it('registers the footer row wrapper on the header hook', function (): void {
    expectAdded('genesis_structural_wrap-footer-widgets')->once()->with('uamswp_filter_structural_wrap', 10, 2);
    LibraryLoader::callback('get_header')();
    expect(has_filter('genesis_structural_wrap-footer-widgets', 'uamswp_filter_structural_wrap'))->toBe(10);
});

it('pins the default Genesis class map and optional filter', function (bool $filtered): void {
    when('esc_attr')->alias(static fn ($value): string => '['.$value.']');
    $classes = [
        'content-sidebar-wrap' => 'row', 'content' => 'col-12 col-md-8', 'sidebar-primary' => 'col-12 col-md-4',
        'sidebar-secondary' => 'col-12 col-sm-2', 'archive-pagination' => 'clearfix', 'entry-content' => 'clearfix',
        'entry-pagination' => 'clearfix', 'comment-list' => 'list-unstyled', 'entry-image' => 'img-fluid',
        'site-header' => '[navbar navbar-expand-sm]', 'footer-widgets' => '[]',
    ];
    if ($filtered) {
        add_filter('uamswp_add_classes', 'strval');
        expectApplied('uamswp_add_classes')->times(3)->with($classes)->andReturn(['filtered' => 'value']);
    }

    expect(uamswp_merge_genesis_attr_classes())->toBe($filtered ? ['filtered' => 'value'] : $classes);
    expect(uamswp_modify_classes_based_on_extras(['ignored'], 'ignored', ['ignored']))->toBe($filtered ? ['filtered' => 'value'] : $classes);
    foreach (array_keys($filtered ? ['filtered' => 'value'] : $classes) as $context) {
        expectAdded('genesis_attr_'.$context)->once()->with('uamswp_add_markup_sanitize_classes', 10, 2);
    }

    uamswp_add_array_filters_genesis_attr();
    foreach (array_keys($filtered ? ['filtered' => 'value'] : $classes) as $context) {
        expect(has_filter('genesis_attr_'.$context, 'uamswp_add_markup_sanitize_classes'))->toBe(10);
    }
})->with([false, true]);

it('sanitizes each extra class while preserving other attributes', function ($value, string $expected, bool $filter): void {
    when('sanitize_html_class')->alias(static fn ($class): string => '['.$class.']');
    if ($filter) {
        add_filter('uamswp_clean_classes_output', 'strval');
        expectApplied('uamswp_clean_classes_output')->once()->with([], 'entry', ['class' => 'base', 'id' => 'keep'])->andReturn($value);
    }

    expect(uamswp_add_markup_sanitize_classes(['class' => 'base', 'id' => 'keep'], 'entry'))->toBe(['class' => 'base '.$expected, 'id' => 'keep']);
})->with([
    [['entry' => ['red', 'blue']], '[red] [blue]', true],
    [['entry' => 'red  blue'], '[red] [] [blue]', true],
    [['entry' => 7], '[7]', true],
    [['other' => ['red']], '', true],
    [[], '', false],
]);

it('pins every supported layout and preserves unrelated classes', function (string $layout, array $overrides): void {
    Functions\expect('genesis_site_layout')->twice()->andReturn($layout);
    $base = ['content' => 'c', 'sidebar-primary' => 'p', 'sidebar-secondary' => 's', 'other' => 'kept'];
    expect(uamswp_layout_options_modify_classes_to_add($base))->toBe(array_replace($base, $overrides));
    expect(uamswp_modify_classes_based_on_template($base))->toBe(array_replace($base, $overrides));
})->with([
    ['full-width-content', ['content' => 'col-sm-12']],
    ['sidebar-content', ['content' => 'col-sm-8', 'sidebar-primary' => 'order-first col-sm-4']],
    ['content-sidebar-sidebar', ['content' => 'col-sm-6', 'sidebar-primary' => 'col-sm-4', 'sidebar-secondary' => 'col-sm-2']],
    ['sidebar-sidebar-content', ['content' => 'col-sm-6 order-3', 'sidebar-primary' => 'col-sm-4 order-2', 'sidebar-secondary' => 'col-sm-2 order-first']],
    ['sidebar-content-sidebar', ['content' => 'col-sm-6', 'sidebar-primary' => 'col-sm-4', 'sidebar-secondary' => 'col-sm-2 order-first']],
    ['content-sidebar', []], ['', []],
]);

it('keeps unrelated breadcrumb arguments and blanks all stock labels', function (): void {
    Functions\expect('genesis_attr')->once()->with('breadcrumb')->andReturn('class="crumbs"');
    $labels = array_fill_keys(['prefix', 'author', 'category', 'tag', 'date', 'search', 'tax', 'post_type', '404'], 'old');
    expect(uamswp_breadcrumb_args(['labels' => $labels + ['other' => 'keep'], 'extra' => 'keep']))->toBe([
        'labels' => array_fill_keys(array_keys($labels), '') + ['other' => 'keep'], 'extra' => 'keep',
        'sep' => '     ', 'prefix' => '<ol class="crumbs">', 'suffix' => '</ol>',
    ]);
});

it('pins breadcrumb links, active items, separators and empty lists', function (): void {
    $schema = ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    expect(uamswp_build_crumbs(['a' => '<a href="/">Home</a>', 'b' => 'Current     Child', 'c' => '</a>at zero']))->toBe([
        'a' => '<li class="breadcrumb-item"'.$schema.'<a itemprop="item" href="/">Home</a></li>',
        'b' => '<li class="breadcrumb-item active"'.$schema.'Current</li><li class="breadcrumb-item active"'.$schema.'Child</li>',
        'c' => '<li class="breadcrumb-item active"'.$schema.'</a>at zero</li>',
    ]);
    expect(uamswp_build_crumbs([]))->toBe([]);
});

it('sets only the documented Customizer setting and removed section', function (): void {
    $customizer = Mockery::mock();
    $customizer->shouldReceive('add_setting')->once()->with('uams-2020', ['capability' => 'edit_theme_options', 'type' => 'theme_mod']);
    $customizer->shouldReceive('remove_section')->once()->with('genesis_breadcrumbs');
    LibraryLoader::callback('customize_register')($customizer);
});

it('pins numeric navigation labels and passes through other options', function ($option, string $previous, string $next): void {
    Functions\expect('genesis_get_option')->twice()->with('posts_nav')->andReturn($option);
    when('__')->alias(static fn ($text, $domain): string => $domain.':'.$text);
    expect(uamswp_genesis_prev_link_text_numeric('P'))->toBe($previous);
    expect(uamswp_genesis_next_link_text_numeric('N'))->toBe($next);
})->with([
    ['numeric', '<span aria-hidden="true">&laquo;</span><span class="sr-only">uams-2020:Previous Page</span>', '<span class="sr-only">uams-2020:Next Page</span><span aria-hidden="true">&raquo;</span>'],
    ['prev-next', 'P', 'N'], [true, 'P', 'N'], ['', 'P', 'N'],
]);

it('rewrites only the page pseudo-name with a present page number', function (array $input, array $expected): void {
    expect(remove_page_from_query_string($input))->toBe($expected);
})->with([
    [[], []], [['name' => null, 'page' => 3], ['name' => null, 'page' => 3]],
    [['name' => 'other', 'page' => 3], ['name' => 'other', 'page' => 3]],
    [['name' => 'page'], ['name' => 'page']], [['name' => 'page', 'page' => null], ['name' => 'page', 'page' => null]],
    [['name' => 'page', 'page' => 0, 'paged' => 4, 'keep' => 'v'], ['page' => 0, 'paged' => 0, 'keep' => 'v']],
    [['name' => 'page', 'page' => '3'], ['page' => '3', 'paged' => '3']],
]);

it('pins the comment box while preserving caller options', function (): void {
    Functions\expect('_x')->once()->with('Comment', 'noun')->andReturn('Comment translated');
    expect(uamswp_comment_form_args(['other' => 'kept', 'class_submit' => 'old']))->toBe([
        'other' => 'kept', 'class_submit' => 'btn btn-primary',
        'comment_field' => '<div class="form-group comment-form-comment">'."\n\t        ".'<label for="comment">Comment translated</label> '."\n\t        ".'<textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>'."\n\t    ".'</div>',
    ]);
});

/**
 * lib/comment.php: IncrementInteger on the HTML5 flag is equivalent because 1 and 2 are both
 * truthy. The flag is only used in the two input-type ternaries.
 */
it('pins optional and required comment fields with both input standards', function (bool $required, bool $html5): void {
    Functions\expect('wp_get_current_commenter')->once()->andReturn(['comment_author' => 'A"', 'comment_author_email' => 'B&', 'comment_author_url' => '<C>']);
    Functions\expect('get_option')->once()->with('require_name_email')->andReturn($required);
    Functions\expect('current_theme_supports')->once()->with('html5', 'comment-form')->andReturn($html5);
    when('__')->alias(static fn ($value): string => 'T:'.$value);
    when('esc_attr')->alias(static fn ($value): string => htmlspecialchars($value, ENT_QUOTES));

    $star = $required ? ' <span class="text-muted required">*</span>' : '';
    $aria = $required ? " aria-required='true'" : '';
    expect(uamswp_comment_form_fields(['ignored' => 'old']))->toBe([
        'author' => '<div class="form-group comment-form-author"><label for="author">T:Name'.$star.'</label> <input class="form-control" id="author" name="author" type="text" value="A&quot;" size="30"'.$aria.' /></div>',
        'email' => '<div class="form-group comment-form-email"><label for="email">T:Email'.$star.'</label> <input class="form-control" id="email" name="email" type="'.($html5 ? 'email' : 'text').'" value="B&amp;" size="30"'.$aria.' /></div>',
        'url' => '<div class="form-group comment-form-url"><label for="url">T:Website</label> <input class="form-control" id="url" name="url" type="'.($html5 ? 'url' : 'text').'" value="&lt;C&gt;" size="30" /></div>',
    ]);
})->with([[false, false], [false, true], [true, false], [true, true]]);

it('pins the full search form including site-scope choices', function (): void {
    Functions\expect('home_url')->once()->with('/')->andReturn('https://search.test/');
    Functions\expect('get_search_query')->once()->andReturn('chosen &amp; text');
    Functions\expect('esc_attr__')->twice()->with('Search', 'uams-2020')->andReturn('Find');
    $expected = file_get_contents(__DIR__.'/../Fixtures/library-search-form.html');
    expect(uamswp_search_form('ignored'))->toBe($expected);
});

it('pins the search tray and delegates the search form once', function (): void {
    Functions\expect('get_search_form')->once()->withNoArgs()->andReturn('<form>SEARCH</form>');
    ob_start();
    uams_toggle_search();
    $actual = ob_get_clean();
    expect($actual)->toBe('<section id="header-search" class="closed" aria-label="Search">'."\n".
        '<div class="inner-container">'."\n".
        '<button type="button" id="toggle-search-inside" aria-controls="header-search" aria-expanded="false" title="Close Search">'."\n".
        '<span class="sr-only label">Toggle Search</span>'."\n".
        '<span class="fas fa-times fa-lg fa-fw"></span>'."\n".
        '</button>'."\n".'<form>SEARCH</form></div>'."\n".'</section>'."\n");
});

it('pins the outer container tags and their whitespace', function (): void {
    ob_start();
    uamswp_sitecontainerouteropen();
    uamswp_sitecontainerouterclose();
    expect(ob_get_clean())->toBe("        <div id=\"site-container-outer\">\n            </div>\n    ");
});

it('preserves stringable structural-wrap markers at the public filter boundary', function (string $marker, string $expected): void {
    expect(uamswp_filter_structural_wrap('BASE', new OptionString($marker)))->toBe($expected);
})->with([['open', 'BASE<div class="row">'], ['close', '</div>BASE']]);

it('rewrites stringable page markers in request-filter input', function (): void {
    expect(remove_page_from_query_string(['name' => new OptionString('page'), 'page' => 3, 'keep' => 'v']))->toBe(['page' => 3, 'keep' => 'v', 'paged' => 3]);
});
