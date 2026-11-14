<?php

declare(strict_types=1);

namespace Uams2020\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Uams2020\Tests\Support\LibraryLoader;
use Uams2020\Tests\Support\PageBuilderLoader;
use Uams2020\Tests\Support\ThemeBootstrap;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\when;
use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class LibraryIsolatedTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        setUp();
        ThemeBootstrap::load();
        PageBuilderLoader::load();
        LibraryLoader::load();
        define('UAMSWP_THEME_MODULES', dirname(__DIR__, 2).'/lib/modules/');
        define('UAMSWP_THEME_CSS', 'https://theme.test/css/');
        define('UAMSWP_THEME_JS', 'https://theme.test/js/');
    }

    protected function tearDown(): void
    {
        $this->addToAssertionCount(\Mockery::getContainer()->mockery_getExpectationCount());
        tearDown();
        parent::tearDown();
    }

    public static function adminStates(): array
    {
        return [[false], [true]];
    }

    #[DataProvider('adminStates')]
    public function test_exact_front_end_asset_queue(bool $admin): void
    {
        expect('wp_get_theme')->once()->andReturn(new class
        {
            public function get(string $key): string
            {
                return $key === 'Version' ? '9.7-test' : '';
            }
        });
        expect('is_admin')->once()->andReturn($admin);
        $calls = [];
        foreach (['wp_enqueue_style', 'wp_deregister_script', 'wp_register_script', 'wp_enqueue_script', 'wp_deregister_style'] as $function) {
            when($function)->alias(static function (...$args) use (&$calls, $function): void {
                $calls[] = [$function, $args];
            });
        }

        expect('get_stylesheet_directory_uri')->times($admin ? 0 : 1)->andReturn('https://style.test');
        uamswp_theme_scripts();
        self::assertSame($admin ? [] : [
            ['wp_enqueue_style', ['app-css', 'https://theme.test/css/app.css', [], '0.0.0-test', 'all']],
            ['wp_enqueue_style', ['google-fonts', '//fonts.googleapis.com/css?family=Fira+Sans:300,300i,500,500i,600,600i,800,800i&display=swap', [], '0.0.0-test']],
            ['wp_deregister_script', ['superfish']], ['wp_deregister_script', ['superfish-args']], ['wp_deregister_script', ['hoverIntent']],
            ['wp_register_script', ['app-js', 'https://theme.test/js/uams.min.js', ['jquery'], '9.7-test', true]], ['wp_enqueue_script', ['app-js']],
            ['wp_register_script', ['fa-js', 'https://theme.test/js/fa.min.js', [], '9.7-test', true]], ['wp_enqueue_script', ['fa-js']],
            ['wp_enqueue_script', ['uams-alert', 'https://style.test/assets/js/uamsalert.js', [], '2.5.0', true]],
            ['wp_deregister_style', ['shortcodes_styles']],
        ], $calls);
    }

    public function test_editor_assets_and_cache_versions(): void
    {
        $root = dirname(__DIR__, 2);
        when('get_stylesheet_directory_uri')->justReturn('https://theme.test');
        when('get_stylesheet_directory')->justReturn($root);
        expect('wp_enqueue_script')->once()->with('theme-editor', 'https://theme.test/assets/js/editor.js', ['wp-blocks', 'wp-dom'], filemtime($root.'/assets/js/editor.js'), true);
        expect('wp_enqueue_script')->once()->with('uamswp-content-transform', 'https://theme.test/assets/js/uams-content-transform.js', ['wp-hooks', 'wp-blocks'], filemtime($root.'/assets/js/uams-content-transform.js'), false);
        uamswp_gutenberg_scripts();
    }

    public static function menuArgumentCases(): array
    {
        return [['primary', ''], ['primary', false], ['primary', 'search'], ['secondary', 'search']];
    }

    #[DataProvider('menuArgumentCases')]
    public function test_primary_menu_arguments(string $location, string|bool $extra): void
    {
        expect('get_theme_mod')->once()->with('navextra', false)->andReturn($extra);
        when('esc_attr')->alias(static fn ($value): string => 'SAFE:'.$value);
        $input = ['theme_location' => $location, 'other' => 'keep'];
        $actual = uamswp_nav_menu_args_filter($input);
        if ($location !== 'primary') {
            self::assertSame($input, $actual);

            return;
        }

        self::assertInstanceOf(\WP_Bootstrap_Navwalker::class, $actual['walker']);
        unset($actual['walker']);
        self::assertSame($input + ['container' => false, 'menu_class' => 'SAFE:nav navbar-nav align-self-end '.($extra !== '' ? 'mr-auto' : 'ml-auto'), 'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback'], $actual);
    }

    public static function pageNavigationCases(): array
    {
        return [
            'existing primary' => [false, true, true, 'unused', []],
            'subsection outside page' => [7, true, false, 'unused', []],
            'subsection empty' => [7, true, true, '', [3, 5]],
            'subsection populated' => [7, true, true, '<li>SUB</li>', [3, 5]],
            'fallback empty' => [false, false, true, '', [3, 5]],
            'fallback populated' => [false, false, true, '<li>ALL</li>', [3, 5]],
            'changing primary' => [false, [true, false], true, '<li>ALL</li>', [3, 5]],
            'numeric hidden subsection' => [7, true, true, '<li>SUB</li>', [3, 5], 1],
            'numeric hidden fallback' => [false, false, true, '<li>ALL</li>', [3, 5], 1],
        ];
    }

    #[DataProvider('pageNavigationCases')]
    public function test_page_navigation_boundaries(bool|int $subsection, bool|array $primary, bool $page, string $pagenav, array $ids, string|int $hidden = '1'): void
    {
        when('get_registered_nav_menus')->justReturn(['primary' => 'Primary']);
        when('uamswp_nav_subsection')->justReturn($subsection);
        if (is_array($primary)) {
            $calls = 0;
            when('has_nav_menu')->alias(static function ($location) use (&$calls, $primary): bool {
                self::assertSame('primary', $location);

                return $primary[min($calls++, count($primary) - 1)];
            });
        } else {
            when('has_nav_menu')->justReturn($primary);
        }

        expect('is_page')->times($subsection ? 1 : 0)->andReturn($page);
        $list = (! $subsection || $page) && ($subsection || (is_array($primary) ? true : ! $primary));
        expect('wp_nav_menu')->times($list ? 1 : 0)->with(['theme_location' => 'primary', 'container' => '', 'menu' => $subsection ? 'subsection-navigation' : 'page-navigation', 'echo' => $subsection ? 0 : false])->andReturn('UNUSED NAV');
        $getPages = expect('get_pages')->times($list ? 1 : 0);
        if ($subsection) {
            $getPages->with(['child_of' => 7]);
        } else {
            $getPages->withNoArgs();
        }

        $getPages->andReturn(array_map(static fn ($id): object => (object) ['ID' => $id], $ids));
        if ($list) {
            foreach ($ids as $id) {
                expect('get_post_meta')->once()->with($id, 'page_hide_from_menu')->andReturn($id === 3 ? [$hidden] : []);
            }

            expect('get_option')->once()->with('page_on_front')->andReturn(11);
            expect('wp_list_pages')->once()->with(\Mockery::on(static function ($args) use ($subsection): bool {
                self::assertInstanceOf(\WP_Bootstrap_Pagewalker::class, $args['walker']);
                unset($args['walker']);
                self::assertSame(($subsection ? ['child_of' => 7] : []) + ['title_li' => '', 'echo' => false, 'exclude_tree' => '3,11'], $args);

                return true;
            }))->andReturn($pagenav);
        }

        expect('genesis_markup')->times($list && $pagenav !== '' ? 1 : 0)->with(['html5' => '<nav %s>', 'xhtml' => '<div id="pagenav">', 'context' => 'genesis-nav-primary', 'echo' => false])->andReturn('UNUSED WRAP');
        ob_start();
        custom_nav_menu();
        $actual = ob_get_clean();
        self::assertSame($list && $pagenav !== '' ? '<nav class="site-nav navbar navbar-expand-sm"><div class="collapse navbar-collapse inner-container" id="genesis-nav-primary"><ul id="menu-dropdowns" class="nav navbar-nav align-self-end mr-auto">'.$pagenav.'</ul></div></nav>' : '', $actual);
    }
}
