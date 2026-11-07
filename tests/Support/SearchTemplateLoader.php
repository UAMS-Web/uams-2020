<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

use Brain\Monkey\Filters;

use function Brain\Monkey\Actions\expectAdded;
use function Brain\Monkey\Actions\expectRemoved;
use function Brain\Monkey\Functions\expect;

/** Every first load asserts registration, whichever search test runs first. */
final class SearchTemplateLoader
{
    public static function load(): void
    {
        if (function_exists('uamswp_do_search_loop')) {
            return;
        }

        expectRemoved('genesis_loop')->once()->with('genesis_do_loop');
        expectAdded('genesis_loop')->once()->with('uamswp_do_search_loop');
        Filters\expectAdded('seopress_titles_robots')->once()->with('sp_titles_robots', 8);
        expect('remove_all_actions')->once()->with('wp_robots');
        expectRemoved('genesis_meta')->once()->with('genesis_robots_meta');
        expectRemoved('genesis_after_header')->once()->with('page_options', 5);
        expectAdded('genesis_before_loop')->once()->with('uamswp_search_page_before_entry', 5);
        expectAdded('genesis_before_loop')->once()->with('uamswp_search_page_heading', 8);
        expectAdded('genesis_after_loop')->once()->with('uamswp_search_page_after_entry', 10);
        Filters\expectAdded('genesis_breadcrumb_args')->once()->with('uamswp_prefix_search_breadcrumb');
        Filters\expectAdded('get_the_excerpt')->once()->with('strip_shortcodes', 20);
        Filters\expectAdded('excerpt_more')->once()->with('new_excerpt_more');
        Filters\expectAdded('excerpt_length')->once()->with('uamswp_excerpt_length');
        expect('genesis')->once();
        require dirname(__DIR__, 2).'/search.php';

    }
}
