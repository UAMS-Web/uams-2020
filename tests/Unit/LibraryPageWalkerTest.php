<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\LibraryWarnings;
use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;

it('labels nested menus from the final link ID and preserves depth indentation', function (int $depth, string $expected): void {
    $walker = new WP_Bootstrap_Pagewalker;
    $output = '<a id="first">A</a><a id="last">B</a>';
    $walker->start_lvl($output, $depth);
    expect($output)->toBe('<a id="first">A</a><a id="last">B</a>'.$expected);
})->with([[0, "\n<ul role=\"menu\" class=\" dropdown-menu\" aria-labelledby=\"last\">\n"], [1, "\n\t<ul role=\"menu\" class=\" dropdown-menu\" aria-labelledby=\"last\">\n"], [2, "\n\t\t<ul role=\"menu\" class=\" dropdown-menu\" aria-labelledby=\"last\">\n"]]);

it('pins the no-ID submenu warning with the default depth', function (string $prefix): void {
    $walker = new WP_Bootstrap_Pagewalker;
    $output = $prefix;
    LibraryWarnings::capture([$walker, 'start_lvl'], 'Undefined variable $labledby', static function () use ($walker, &$output): void {
        $walker->start_lvl($output);
    });
    expect($output)->toBe($prefix."\n<ul role=\"menu\" class=\" dropdown-menu\" >\n");
})->with(['<a>None</a>', '<a id="0">Zero</a>']);

/**
 * Page-walker equivalents: EmptyStringToNotEmpty on initial alt_name is overwritten by metadata.
 * ConcatEqualToEqual on the first item append starts from an empty local string. All six
 * concatenation mutations in the icon-building statement are unreachable because icon_class_string
 * is a local constant empty string. Removing or swapping the empty icon_html operand before the
 * filtered title is equivalent; removing the title remains active. The repeated title predicate
 * remains mutable because real metadata-backed WP_Post getters can change between reads.
 */
it('pins page-walker class selection, labels, links and page-title mutation', function (array $case): void {
    $walker = new WP_Bootstrap_Pagewalker;
    $page = (object) ['ID' => 41, 'post_title' => $case['title'] ?? '<b>Title</b>'];
    $depth = $case['depth'] ?? 0;
    $current = $case['current'] ?? 0;
    $args = $case['args'] ?? [];
    when('__')->alias(static fn ($text): string => $text);
    Functions\expect('get_post')->times(empty($current) ? 0 : 1)->with($current)->andReturn((object) ['ancestors' => $case['ancestors'] ?? [], 'post_parent' => $case['parent'] ?? 0]);
    Functions\expect('get_option')->times(empty($current) ? 1 : 0)->with('page_for_posts')->andReturn($case['posts_page'] ?? 0);
    Functions\expect('get_post_meta')->once()->with(41, 'page_nav_alt_name', true)->andReturn($case['alt'] ?? '');
    when('get_permalink')->alias(static function ($id) use ($case): string {
        expect($id)->toBe(41);

        return $case['url'] ?? '/p/41';
    });
    when('esc_attr')->alias(static fn ($value): string => htmlspecialchars($value, ENT_QUOTES));
    when('esc_url')->alias(static fn ($value): string => 'SAFE:'.$value);
    expectApplied('page_css_class')->once()->with($case['classes'], $page, $depth, $args, $current)->andReturn($case['filtered_classes'] ?? $case['classes']);
    expectApplied('nav_menu_item_id')->once()->with('page-item-41', $page, $args)->andReturn($case['item_id'] ?? 'page-item-41');
    $title = $case['render_title'] ?? ($case['alt'] ?? ($case['title'] ?? '<b>Title</b>'));
    if ($title === '') {
        $title = '#41 (no title)';
    }

    expectApplied('the_title')->once()->with($title, 41)->andReturn('FILTERED TITLE');
    $item = '<a'.$case['attributes'].'>FILTERED TITLE</a>';
    expectApplied('walker_nav_menu_start_el')->once()->with($item, $page, $depth, $args)->andReturn($item.'[FILTERED]');
    $output = 'PREFIX';
    if ($case['defaults'] ?? false) {
        $walker->start_el($output, $page);
    } else {
        $walker->start_el($output, $page, $depth, $args, $current);
    }

    expect($output)->toBe('PREFIX'.str_repeat("\t", $depth).'<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"'.$case['li'].'>'.$item.'[FILTERED]');
    expect($page->post_title)->toBe($title);
})->with([
    [['defaults' => true, 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['depth' => 2, 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="dropdown-item"']],
    [['depth' => 1, 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="dropdown-item"']],
    [['args' => ['pages_with_children' => [41 => true]], 'classes' => ['page-item-41', 'nav-item', 'page-item-has-children dropdown'], 'li' => ' id="page-item-41" class="page-item-41 nav-item page-item-has-children dropdown"', 'attributes' => ' title="Title" href="SAFE:/p/41" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle nav-link" id="menu-item-dropdown-41"']],
    [['current' => 41, 'ancestors' => [41], 'classes' => ['page-item-41', 'nav-item', 'current-page-ancestor', 'current-page-item', ' active'], 'li' => ' id="page-item-41" class="page-item-41 nav-item current-page-ancestor current-page-item  active"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['current' => '41', 'ancestors' => [41], 'classes' => ['page-item-41', 'nav-item', 'current-page-ancestor', 'current-page-item', ' active'], 'li' => ' id="page-item-41" class="page-item-41 nav-item current-page-ancestor current-page-item  active"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['current' => 99, 'parent' => 41, 'ancestors' => ['41'], 'classes' => ['page-item-41', 'nav-item', 'current-page-ancestor', 'current-page-parent', ' active'], 'li' => ' id="page-item-41" class="page-item-41 nav-item current-page-ancestor current-page-parent  active"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['current' => 99, 'parent' => '41', 'ancestors' => ['41'], 'classes' => ['page-item-41', 'nav-item', 'current-page-ancestor', 'current-page-parent', ' active'], 'li' => ' id="page-item-41" class="page-item-41 nav-item current-page-ancestor current-page-parent  active"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['current' => 99, 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['posts_page' => '41', 'classes' => ['page-item-41', 'nav-item', 'current-page-parent', ' active'], 'li' => ' id="page-item-41" class="page-item-41 nav-item current-page-parent  active"', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
    [['alt' => 'ALT <&>', 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="ALT &lt;&amp;&gt;; Title" href="SAFE:/p/41" class="nav-link"']],
    [['title' => '', 'url' => '', 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="#41 (no title)" href="SAFE:#" class="nav-link"']],
    [['title' => 1, 'render_title' => 1, 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="1" href="SAFE:/p/41" class="nav-link"']],
    [['title' => new OptionString('Boundary title'), 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="Boundary title" href="SAFE:/p/41" class="nav-link"']],
    [['title' => '0', 'render_title' => '0', 'classes' => ['page-item-41', 'nav-item'], 'li' => ' id="page-item-41" class="page-item-41 nav-item"', 'attributes' => ' title="#41 (no title)" href="SAFE:/p/41" class="nav-link"']],
    [['filtered_classes' => [], 'item_id' => '', 'classes' => ['page-item-41', 'nav-item'], 'li' => '', 'attributes' => ' title="Title" href="SAFE:/p/41" class="nav-link"']],
]);

it('sets child state and delegates the original traversal arguments', function ($element, array $children, $arg, bool $expected): void {
    $walker = new WP_Bootstrap_Pagewalker;
    $output = 'BEFORE';
    $args = [$arg];
    $walker->display_element($element, $children, 4, 2, $args, $output);
    if (! $element) {
        expect($output)->toBe('BEFORE')->and($walker->delegated)->toBe([]);
    } else {
        if (is_object($arg)) {
            expect($arg->has_children)->toBe($expected);
        }

        expect($output)->toBe('BEFORE[PARENT]')->and($walker->delegated)->toBe([[$element, $children, 4, 2, $args, 'BEFORE']]);
    }
})->with([[null, [], (object) [], false], [(object) ['ID' => 41], [41 => ['child']], (object) [], true], [(object) ['ID' => 41], [40 => ['child']], (object) [], false], [(object) ['ID' => 41], [], [], false]]);

it('allows the page-walker definition file to be included after the class already exists', function (): void {
    require dirname(__DIR__, 2).'/lib/modules/class-wp-bootstrap-pagewalker.php';
    expect(str_replace('\\', '/', (new ReflectionClass(WP_Bootstrap_Pagewalker::class))->getFileName()))->toBe(str_replace('\\', '/', dirname(__DIR__, 2)).'/lib/modules/class-wp-bootstrap-pagewalker.php');
});

it('rechecks metadata-backed page titles before choosing the title attribute', function (): void {
    $page = new class
    {
        public int $ID = 41;

        private int $reads = 0;

        public function __isset(string $name): bool
        {
            return $name === 'post_title';
        }

        public function __get(string $name): string
        {
            return $this->reads++ < 2 ? 'Initial title' : '';
        }
    };
    when('get_option')->justReturn(0);
    when('get_post_meta')->justReturn('');
    when('get_permalink')->justReturn('/p/41');
    when('esc_url')->alias(static fn ($value) => $value);
    when('esc_attr')->alias(static fn ($value) => $value);
    foreach (['page_css_class', 'nav_menu_item_id', 'the_title', 'walker_nav_menu_start_el'] as $filter) {
        expectApplied($filter)->once()->andReturnUsing(static fn ($value) => $value);
    }

    $output = '';
    (new WP_Bootstrap_Pagewalker)->start_el($output, $page);
    expect($output)->not->toContain(' title=')->toContain('<a href="/p/41" class="nav-link"></a>');
});
