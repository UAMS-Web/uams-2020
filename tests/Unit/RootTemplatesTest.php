<?php

declare(strict_types=1);

use Brain\Monkey\Actions;
use Brain\Monkey\Functions;

use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Filters\expectAdded;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

it('renders the posts home with title and excerpt filters', function (): void {
    expectAdded('genesis_post_title_output')->once()->with('uamswp_post_title_link');
    expectAdded('get_the_excerpt')->once()->with('uamswp_link_excerpt_more');
    Functions\expect('genesis')->once();
    when('get_the_title')->justReturn('A title & more');
    when('get_the_permalink')->justReturn('https://example.test/post/');
    when('esc_url')->returnArg();
    when('esc_attr')->alias(htmlspecialchars(...));
    when('esc_html__')->returnArg();

    require dirname(__DIR__, 2).'/home.php';

    expect(uamswp_post_title_link('ignored'))->toBe('<h2 class="module-title" itemprop="headline"><span class="title">A title & more</span></h2>')
        ->and(uamswp_link_excerpt_more('<p>Excerpt'))->toBe('<p>Excerpt</p><p><a href="https://example.test/post/" class="btn btn-outline-primary" rel="bookmark" aria-label="A title &amp; more">Read More</a>');
});

it('renders the archive with both accessible sitemap headings', function (): void {
    expectRemoved('genesis_entry_content')->once()->with('genesis_do_post_content');
    Actions\expectAdded('genesis_entry_content')->once()->with('genesis_page_archive_content');
    Functions\expect('genesis')->once();
    Functions\expect('genesis_a11y')->twice()->with('headings')->andReturn(true, false);
    Functions\expect('genesis_sitemap')->once()->with('h2')->ordered();
    Functions\expect('genesis_sitemap')->once()->with('h4')->ordered();

    require dirname(__DIR__, 2).'/page_archive.php';
    genesis_page_archive_content();
    genesis_page_archive_content();
});

it('renders the plain blog template', function (): void {
    Functions\expect('genesis')->once();
    require dirname(__DIR__, 2).'/page_blog.php';
});

it('renders the modules template with its content callback', function (): void {
    expectAdded('genesis_pre_get_option_site_layout')->once()->with('__genesis_return_full_width_content');
    expectRemoved('genesis_sidebar')->once()->with('genesis_do_sidebar');
    expectRemoved('genesis_entry_content')->once()->with('genesis_do_post_content');
    Actions\expectAdded('genesis_entry_content')->once()->with('uamswp_modules_display');
    expectRemoved('genesis_entry_footer')->once()->with('genesis_post_meta');
    Functions\expect('uamswp_modules')->once();
    Functions\expect('genesis')->once();

    require dirname(__DIR__, 2).'/templates/modules.php';
    uamswp_modules_display();
});

it('hides the landing title while preserving existing attributes', function (): void {
    expectAdded('genesis_attr_entry-title')->once()->with('uamswp_add_class');
    Functions\expect('genesis')->once();

    require dirname(__DIR__, 2).'/templates/page_landing.php';

    expect(uamswp_add_class(['class' => 'entry-title', 'id' => 'title']))->toBe(['class' => 'entry-title sr-only', 'id' => 'title'])
        ->and(uamswp_add_class(['class' => '']))->toBe(['class' => ' sr-only']);
});

it('registers the marketing layout and selects each header mode', function (): void {
    foreach ([
        ['genesis_entry_footer', 'genesis_post_meta', 10],
        ['genesis_after_header', 'genesis_do_nav', 10],
        ['genesis_after_header', 'custom_nav_menu', 5],
        ['genesis_after_header', 'genesis_do_breadcrumbs', 10],
        ['genesis_after_header', 'sp_breadcrumb_after_header', 10],
        ['genesis_after_entry', 'uamswp_list_child_pages', 10],
        ['genesis_after_header', 'page_options', 5],
    ] as [$hook, $callback, $priority]) {
        expectRemoved($hook)->once()->with($callback, $priority);
    }

    Actions\expectAdded('genesis_after_header')->once()->with('mlp_header_options', 5);
    Functions\expect('genesis')->once();

    require dirname(__DIR__, 2).'/templates/marketing.php';

    // Verify registrations before resetting the hook mocks for each header case.
    tearDown();
    $stringHero = new class implements Stringable
    {
        public function __toString(): string
        {
            return 'hero';
        }
    };
    foreach ([[false, ''], ['', ''], ['ordinary', 'ordinary'], ['hero', 'hero'], ['landingpage', 'landingpage'], [true, 'landingpage'], [$stringHero, 'hero']] as [$option, $mode]) {
        setUp();
        Functions\expect('get_the_id')->once()->andReturn(42);
        Functions\expect('get_field')->with('page_title_options', 42)->times($option ? 2 : 1)->andReturn($option);
        if ($mode === 'hero' || $mode === 'landingpage') {
            expectRemoved('genesis_entry_header')->once()->with('genesis_entry_header_markup_open', 5);
            expectRemoved('genesis_entry_header')->once()->with('genesis_do_post_title');
            expectRemoved('genesis_entry_header')->once()->with('genesis_entry_header_markup_close', 15);
        } else {
            expectRemoved('genesis_entry_header')->never();
        }

        $actions = match ($mode) {
            'hero' => [['genesis_entry_header_markup_open', 5], ['genesis_do_post_title', 10], ['genesis_entry_header_markup_close', 15], ['uamswp_page_hero', 20]],
            'landingpage' => [
                ['uamswp_landing_page_title_wrap_open', 5], ['genesis_entry_header_markup_open', 5],
                ['uamswp_landing_page_title_inner_1', 5], ['uamswp_landing_page_title_do_post_title', 10],
                ['uamswp_landing_page_title_inner_2', 15], ['uamswp_landing_page_title_lead_paragraph', 15],
                ['uamswp_landing_page_title_inner_3', 15], ['genesis_entry_header_markup_close', 15],
                ['uamswp_landing_page_title_wrap_close', 15],
            ],
            default => [],
        };
        foreach ($actions as [$callback, $priority]) {
            Actions\expectAdded('genesis_before_content')->once()->with($callback, $priority);
        }

        if ($actions === []) {
            Actions\expectAdded('genesis_before_content')->never();
        }

        if ($option) {
            expectAdded('genesis_attr_entry-header')->once()->with($mode === 'landingpage' ? 'uamswp_attributes_entry_header_landing_page_title' : 'uamswp_attributes_entry_header');
        } else {
            expectAdded('genesis_attr_entry-header')->never();
        }

        mlp_header_options();
        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
        tearDown();
    }

    setUp();
});
