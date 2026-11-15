<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\LibraryLoader;

use function Brain\Monkey\Functions\when;

it('adds responsive classes only to images with a double-quoted class', function (): void {
    expect(uamswp_image_responsive_class('<img src="x" class="a" alt="b"><IMG class="" src="y"><img src="z"><img class=\'single\'>'))->toBe('<img src="x" class="a img-fluid" alt="b"><img class=" img-fluid" src="y"><img src="z"><img class=\'single\'>');
});

it('returns an empty string for image content that is not text', function ($content): void {
    expect(uamswp_image_responsive_class($content))->toBe('');
})->with([[['<img class="a">', 'plain']], [null], [false], [new stdClass]]);

it('returns an empty string when the pattern engine fails', function (): void {
    $limit = ini_get('pcre.backtrack_limit');
    ini_set('pcre.backtrack_limit', '1');

    try {
        $subject = '<p>'.str_repeat('a ', 200).'<img src="x" /></p>';
        expect(uamswp_image_responsive_class('<img'.str_repeat(' a', 200).' class="x">'))->toBe('')
            ->and(uamswp_filter_ptags_on_images($subject))->toBe('');
    } finally {
        ini_set('pcre.backtrack_limit', (string) $limit);
    }
});

it('keeps numeric and boolean image content as text', function ($content, string $expected): void {
    expect(uamswp_image_responsive_class($content))->toBe($expected);
})->with([[42, '42'], [true, '1']]);

it('pins the category count replacement for strings and arrays', function (): void {
    $open = '<span class="badge badge-pill badge-primary tag-default post-count">';
    expect(uamswp_categories_postcount_filter('Name (2) (3)'))->toBe('Name '.$open.'2</span> '.$open.'3</span>');
});

it('returns an empty string for category markup that is not text', function ($variable): void {
    expect(uamswp_categories_postcount_filter($variable))->toBe('');
})->with([[['(0)', 'plain']], [null], [false]]);

it('adds the blog body class only on the blog page template', function (bool $blog): void {
    Functions\expect('is_page_template')->once()->with('page_blog.php')->andReturn($blog);
    expect(page_blog_class(['base']))->toBe($blog ? ['base', 'blog'] : ['base']);
})->with([true, false]);

it('pins the viewport and custom-logo replacements', function (): void {
    expect(uamswp_viewport_value())->toBe('width=device-width, initial-scale=1, shrink-to-fit=no');
    expect(LibraryLoader::callback('get_custom_logo')('<a class="custom-logo-link">custom-logo-link</a>'))->toBe('<a class="navbar-brand">navbar-brand</a>');
});

it('pins password form labels with both saved and missing post IDs', function ($id): void {
    global $post;
    $old = $post ?? null;
    $post = (object) ['ID' => $id];
    when('__')->alias(static fn ($text): string => 'T:'.$text);
    Functions\expect('site_url')->once()->with('wp-login.php?action=postpass', 'login_post')->andReturn('https://site.test/login?pass');
    Functions\expect('esc_url')->once()->with('https://site.test/login?pass')->andReturn('URL');
    Functions\expect('esc_attr__')->once()->with('Submit')->andReturn('Send');
    if (empty($id)) {
        Functions\expect('mt_getrandmax')->once()->withNoArgs()->andReturn(991);
        Functions\expect('random_int')->once()->with(0, 991)->andReturn(73);
    } else {
        Functions\expect('random_int')->never();
    }

    try {
        expect(LibraryLoader::callback('the_password_form')())->toBe('<p>T:To view this protected post, enter the password below:</p><form class="form-inline" action="URL" method="post"><label for="pwbox-'.($id ?: 73).'" class="sr-only">T:Password: </label><input class="form-control mr-2"name="post_password" id="pwbox-'.($id ?: 73).'" type="password" size="20" maxlength="20" /><input type="submit" class="btn btn-primary" name="Submit" value="Send" />'."\n    </form>\n    ");
    } finally {
        $post = $old;
    }
})->with([91, 0, null]);

it('replaces widget-area defaults with exact Genesis markup arguments', function (): void {
    Functions\expect('genesis_markup')->once()->with(['open' => '<section id="%%1$s" class="widget %%2$s">', 'context' => 'widget-wrap', 'echo' => false])->andReturn('OPEN');
    Functions\expect('genesis_markup')->once()->with(['close' => '</section>\\n', 'context' => 'widget-wrap', 'echo' => false])->andReturn('CLOSE');
    expect(LibraryLoader::callback('genesis_register_widget_area_defaults')(['old' => 'discarded']))->toBe([
        'before_widget' => 'OPEN', 'after_widget' => 'CLOSE', 'before_title' => '<h4 class="widget-title widgettitle">', 'after_title' => '</h4><div class="widget-wrap">',
    ]);
});

it('pins page-link wrappers while preserving other options', function (): void {
    expect(LibraryLoader::callback('wp_link_pages_args')(['other' => 'kept', 'before' => 'old']))->toBe(['other' => 'kept', 'before' => '<ul class="post-pagination">', 'after' => '</ul>']);
});

it('pins current and linked page-number output', function (string $link, string $expected): void {
    expect(LibraryLoader::callback('wp_link_pages_link')($link))->toBe($expected);
})->with([
    ['12', '<li class="page-item active"><a href="#">12</a></li>'],
    ['<a href="/2">2</a>', '<li class="page-item"><a href="/2">2</a></li>'],
    ['', '<li class="page-item"></li>'],
]);

it('uses custom footer credits only when the setting is truthy', function ($setting): void {
    Functions\expect('get_theme_mod')->once()->with('creds', false)->andReturn($setting);
    if ($setting) {
        Functions\expect('get_theme_mod')->once()->with('creds')->andReturn('SECOND LOOKUP');
    }

    expect(LibraryLoader::callback('genesis_pre_get_option_footer_text')('ORIGINAL'))->toBe($setting ? 'SECOND LOOKUP' : 'ORIGINAL');
})->with([false, '', 'custom']);

it('pins the legacy paragraph-image regular expression', function (string $input, string $expected): void {
    expect(uamswp_filter_ptags_on_images($input))->toBe($expected);
})->with([
    ['<p> <img src="x" /> </p>', '<img src="x" />'],
    ['<p><a href="x"><img src="x" /></a></p>', '<p><a href="x"><img src="x" /></a></p>'],
    ['<p>text <img src="x" /></p>', '<p>text <img src="x" /></p>'],
    ['<p><img src="x"></p>', '<p><img src="x"></p>'],
]);

it('pins the download label regex including its attribute spacing', function (string $input, string $expected): void {
    expect(uamswp_filter_download_button($input))->toBe($expected);
})->with([
    ['<div class="wp-block-file"><a href="f">Guide</a><a href="d">Download</a></div>', '<div class="wp-block-file"><a href="f">Guide</a><a aria-label="Download for Guide" href="d">Download</a></div>'],
    ['<div class="wp-block-file"><a href="f"></a><a href="d">Download</a></div>', '<div class="wp-block-file"><a href="f"></a><a href="d">Download</a></div>'],
    ['plain', 'plain'],
]);

it('pins the one-digit current-page markup without an offset warning', function (): void {
    expect(LibraryLoader::callback('wp_link_pages_link')('1'))->toBe('<li class="page-item active"><a href="#">1</a></li>');
});

it('treats a page link that is not text as an empty item', function ($link): void {
    expect(LibraryLoader::callback('wp_link_pages_link')($link))->toBe('<li class="page-item"></li>');
})->with([[null], [false], [['2']]]);

it('keeps the page-link options only when they are an array', function ($params, array $expected): void {
    expect(LibraryLoader::callback('wp_link_pages_args')($params))->toBe($expected);
})->with([
    [null, ['before' => '<ul class="post-pagination">', 'after' => '</ul>']],
    ['text', ['before' => '<ul class="post-pagination">', 'after' => '</ul>']],
]);

it('adds the blog body class to a body-class list that is not an array', function ($classes): void {
    Functions\expect('is_page_template')->once()->with('page_blog.php')->andReturn(true);
    expect(page_blog_class($classes))->toBe(['blog']);
})->with([[null], [false], ['base']]);

it('leaves the paragraph-image and download content filters empty for non-text content', function ($content): void {
    expect(uamswp_filter_ptags_on_images($content))->toBe('')->and(uamswp_filter_download_button($content))->toBe('');
})->with([[null], [false], [['<p></p>']]]);

it('builds the download label from missing capture groups without warnings', function (): void {
    expect(add_download_title(null))->toBe(' aria-label="Download for "')->and(add_download_title(['<a']))->toBe('<a aria-label="Download for "');
});

it('replaces the custom logo class only in text markup', function ($html): void {
    expect(LibraryLoader::callback('get_custom_logo')($html))->toBe('');
})->with([[null], [false], [['custom-logo-link']]]);

it('uses a random password-form label when the post is missing or not an object', function ($post): void {
    $old = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = $post;
    when('__')->alias(static fn ($text): string => 'T:'.$text);
    Functions\expect('site_url')->once()->andReturn('u');
    Functions\expect('esc_url')->once()->andReturn('URL');
    Functions\expect('esc_attr__')->once()->andReturn('Send');
    Functions\expect('mt_getrandmax')->once()->andReturn(991);
    Functions\expect('random_int')->once()->with(0, 991)->andReturn(73);

    try {
        expect(LibraryLoader::callback('the_password_form')())->toContain('for="pwbox-73"')->toContain('id="pwbox-73"');
    } finally {
        $GLOBALS['post'] = $old;
    }
})->with([[null], [false], ['91']]);

it('preserves the complete capture-array contract of the download-title callback', function (): void {
    expect(add_download_title(['<a', 'Guide', ' href="/file">Download</a>']))->toBe('<a aria-label="Download for Guide" href="/file">Download</a>');
});
