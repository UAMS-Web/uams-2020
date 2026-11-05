<?php

declare(strict_types=1);

use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use Uams2020\Tests\Support\SearchTemplateLoader;

use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Filters\expectAdded;
use function Brain\Monkey\Functions\when;

beforeEach(function (): void {
    $this->originalSearchGet = $_GET;
    $_GET = [];
    SearchTemplateLoader::load();
});

afterEach(function (): void {
    $_GET = $this->originalSearchGet;
});

it('sets search robots markup and excerpt defaults', function (): void {
    when('get_permalink')->justReturn('https://example.test/article/');
    expect(sp_titles_robots('original'))->toBe('<meta name="robots" content="noindex, noarchive, nosnippet"/><meta name="google" content="noimageindex">')
        ->and(uamswp_content_limit())->toBe('150')
        ->and(uamswp_show_excerpts())->toBe('excerpts')
        ->and(uamswp_excerpt_length(55))->toBe(20)
        ->and(uamswp_read_more_link())->toBe('... <a class="more-link" href="https://example.test/article/">Continue Reading</a>')
        ->and(new_excerpt_more('old'))->toBe('... <a class="more-link" href="https://example.test/article/">Continue Reading</a>')
        ->and(uamswp_prefix_search_breadcrumb(['labels' => ['other' => 'keep'], 'sep' => '/']))->toBe(['labels' => ['other' => 'keep', 'search' => 'Search results for '], 'sep' => '/']);
});

it('prints the search page wrapper and escapes its query', function (): void {
    $_GET['s'] = '<script>&';
    when('esc_html')->alias(htmlspecialchars(...));
    ob_start();
    uamswp_search_page_before_entry();
    uamswp_search_page_heading();
    uamswp_search_page_after_entry();
    $html = ob_get_clean();
    expect($html)->toBe('<article class="entry" itemtype="https://schema.org/CreativeWork"><header class="entry-header"><h1 class="entry-title" itemprop="headline">Search results for: &lt;script&gt;&amp;</h1></header></article>');
});

it('prints an empty search heading when the query is absent', function (): void {
    ob_start();
    uamswp_search_page_heading();
    expect(ob_get_clean())->toBe('<header class="entry-header"><h1 class="entry-title" itemprop="headline">Search results for: </h1></header>');
});

it('queries allowed card and taxonomy types with the current page size', function ($type, $size, $heading, $page, bool $queryPresent = true): void {
    $_GET = ['type' => $type];
    if ($queryPresent) {
        $_GET['s'] = 'heart';
    }

    when('esc_html')->returnArg();
    Functions\expect('get_query_var')->once()->with('paged')->andReturn($page);
    Functions\expect('uamswp_custom_loop_base')->once()->with(['s' => $queryPresent ? 'heart' : '', 'post_type' => $type, 'posts_per_page' => $size, 'paged' => $page ?: 1]);
    Functions\expect('have_posts')->once()->andReturn(false);
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    expect($html)->toContain('<h2 class="module-title"><span class="title">'.$heading.'</span></h2>')
        ->toContain('Sorry, no content matched your criteria.')
        ->not->toContain('Search All UAMS Sites');
})->with([
    ['provider', 6, 'Providers', 0], ['location', 10, 'Locations', 2],
    ['expertise', 10, 'Services', 1], ['condition', 10, 'Conditions', 0],
    ['treatment', 10, 'Treatments &amp; Procedures', 3],
    ['location', 10, 'Locations', 0], ['expertise', 10, 'Services', 0],
    ['treatment', 10, 'Treatments &amp; Procedures', 0], ['provider', 6, 'Providers', 0, false],
]);

/**
 * StrEndsWithToStrStartsWith is equivalent for this branch's only labels, Page/Post:
 * neither starts nor ends with lowercase s.
 */
it('uses the standard loop for a requested page or post', function ($type, $heading, int $page = 4): void {
    $_GET = ['s' => 'news', 'type' => $type];
    when('esc_html')->returnArg();
    when('get_query_var')->justReturn($page);

    Functions\expect('uamswp_loop_layout')->once();
    Functions\expect('uamswp_custom_loop')->once()->with(['s' => 'news', 'post_type' => $type, 'paged' => $page ?: 1]);
    ob_start();
    uamswp_do_search_loop();
    expect(ob_get_clean())->toBe('<div class="uams-module bg-auto"><div class="container-fluid"><div class="search-content row"><div class="col-12"><h2 class="module-title"><span class="title">'.$heading.'</span></h2><div class="module-body"></div></div></div></div></div>');
})->with([['page', 'Pages'], ['post', 'Posts'], ['page', 'Pages', 0], ['post', 'Posts', 0]]);

it('rejects an unsupported search type and queries pages then posts', function ($type): void {
    $_GET = ['s' => 'news'];
    if ($type !== null) {
        $_GET['type'] = $type;
    }

    when('esc_html')->returnArg();
    Functions\expect('uamswp_custom_loop_base')->once()->ordered()->with(['s' => 'news', 'post_type' => 'page', 'posts_per_page' => 5]);
    Functions\expect('uamswp_custom_loop_base')->once()->ordered()->with(['s' => 'news', 'post_type' => 'post', 'posts_per_page' => 5]);
    Functions\expect('have_posts')->twice()->andReturn(false);
    Functions\expect('wp_reset_query')->twice();
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    expect($html)->toContain('>Pages</span>', '>Posts</span>', 'Search All UAMS Sites', 'data-queryParameterName="s"')
        ->and(substr_count($html, 'Sorry, no content matched your criteria.'))->toBe(2);
})->with([[null], ['private'], ['Provider'], [''], [['post']]]);

it('pins the search loop layout callbacks and priorities', function (): void {
    foreach ([['genesis_entry_header', 'genesis_post_info', 12], ['genesis_entry_content', 'genesis_do_post_image', 8], ['genesis_entry_content', 'genesis_do_post_content_nav', 12], ['genesis_entry_content', 'genesis_do_post_permalink', 14], ['genesis_entry_footer', 'genesis_entry_footer_markup_open', 5], ['genesis_entry_footer', 'genesis_entry_footer_markup_close', 15], ['genesis_entry_footer', 'genesis_post_meta', 10]] as [$hook, $callback, $priority]) {
        expectRemoved($hook)->once()->with($callback, $priority);
    }

    expectAdded('genesis_pre_get_option_content_archive_limit')->once()->with('uamswp_content_limit');
    expectAdded('get_the_content_more_link')->once()->with('uamswp_read_more_link');
    Filters\expectRemoved('genesis_post_title_output')->once()->with('uamswp_entry_title_h1');
    expectAdded('genesis_post_title_output')->once()->with('uamswp_search_title');
    uamswp_loop_layout();
});

it('restores the current blog after building a multisite result title', function (): void {
    $originalPost = $GLOBALS['post'] ?? null;
    $GLOBALS['post'] = (object) ['ID' => 42, 'blog_id' => 7];
    Functions\expect('switch_to_blog')->once()->with(7)->ordered();
    Functions\expect('get_the_title')->once()->with(42)->ordered()->andReturn('Result');
    Functions\expect('get_the_permalink')->once()->with(42)->ordered()->andReturn('https://example.test/result/');
    Functions\expect('restore_current_blog')->once()->ordered();
    try {
        expect(uamswp_search_title('ignored'))->toBe('<h2 class="entry-title" itemprop="headline"><a href="https://example.test/result/">Result</a></h2>');
    } finally {
        $GLOBALS['post'] = $originalPost;
    }
});
