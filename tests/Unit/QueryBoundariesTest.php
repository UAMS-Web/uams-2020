<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\QueryDouble;
use Uams2020\Tests\Support\SearchTemplateLoader;

use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

beforeEach(function (): void {
    $this->savedQueryGlobals = [];
    foreach (['wp_query', 'post', 'more'] as $key) {
        $this->savedQueryGlobals[$key] = [array_key_exists($key, $GLOBALS), $GLOBALS[$key] ?? null];
    }

    if (! class_exists('WP_Query')) {
        class_alias(QueryDouble::class, 'WP_Query');
    }

    SearchTemplateLoader::load();
    QueryDouble::$nextRows = [];
    QueryDouble::$lastArgs = [];
});

afterEach(function (): void {
    QueryDouble::$nextRows = [];
    QueryDouble::$lastArgs = [];
    foreach ($this->savedQueryGlobals as $key => [$existed, $value]) {
        if ($existed) {
            $GLOBALS[$key] = $value;
        } else {
            unset($GLOBALS[$key]);
        }
    }
});

it('filters custom query arguments, optionally runs Relevanssi and resets only the full loop', function (bool $full, bool $singular, bool $relevanssi): void {
    $GLOBALS['more'] = 9;
    Functions\expect('wp_parse_args')->once()->with(['s' => 'heart'], [])->andReturn(['s' => 'heart']);
    expectApplied('genesis_custom_loop_args')->once()->with(['s' => 'heart'], ['s' => 'heart'], [])->andReturn(['s' => 'heart', 'posts_per_page' => 3]);
    when('is_singular')->justReturn($singular);
    Functions\expect('relevanssi_do_query')->times($relevanssi ? 1 : 0)->with(Mockery::type(QueryDouble::class));
    Functions\expect('genesis_standard_loop')->times($full ? 1 : 0);
    Functions\expect('wp_reset_query')->times($full ? 1 : 0);

    when('function_exists')->alias(static fn ($function) => $function === 'relevanssi_do_query' ? $relevanssi : relay());

    if ($full) {
        uamswp_custom_loop(['s' => 'heart']);
    } else {
        uamswp_custom_loop_base(['s' => 'heart']);
    }

    expect($GLOBALS['wp_query'])->toBeInstanceOf(QueryDouble::class)
        ->and($GLOBALS['wp_query']->args)->toBe(['s' => 'heart', 'posts_per_page' => 3])
        ->and($GLOBALS['more'])->toBe($singular ? 9 : 0);
})->with([[true, true, true], [true, false, false], [false, true, false], [false, false, true]]);

it('renders child-post queries and resets post data only when results exist', function ($type, $title, array $rows): void {
    QueryDouble::$nextRows = $rows;
    Functions\expect('get_the_id')->once()->andReturn(42);
    when('get_permalink')->alias(static fn (): string => 'https://example.test/child/'.$GLOBALS['post']->ID);
    when('get_the_title')->alias(static fn (): string => 'Child '.$GLOBALS['post']->ID);

    Functions\expect('wp_reset_postdata')->times($rows === [] ? 0 : 1);
    ob_start();
    uamswp_list_child_posts($type, $title);
    $html = ob_get_clean();
    expect(QueryDouble::$lastArgs)->toBe([
        'post_type' => $type ?? 'page', 'post_status' => 'publish', 'post_parent' => 42,
        'order' => 'ASC', 'orderby' => 'menu_order title', 'posts_per_page' => -1,
        'meta_query' => [['key' => 'page_hide_from_menu', 'value' => '1', 'compare' => '!=']],
    ]);
    if ($rows === []) {
        expect($html)->toBe('');
    } else {
        expect($html)->toContain('<span class="title">'.($title ?? 'Subpages').'</span>');
        foreach ($rows as $row) {
            expect($html)->toContain('<a href="https://example.test/child/'.$row['ID'].'">Child '.$row['ID'].'</a>');
        }
    }
})->with([[null, null, []], [null, null, [['ID' => 11], ['ID' => 22]]], ['post', 'News', [['ID' => 33]]]]);
