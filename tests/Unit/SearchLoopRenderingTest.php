<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\HtmlFixture;
use Uams2020\Tests\Support\SearchTemplateLoader;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

beforeEach(function (): void {
    $this->savedSearchGlobals = [];
    foreach (['_GET', 'post', 'wp_query', 'more'] as $key) {
        $this->savedSearchGlobals[$key] = [array_key_exists($key, $GLOBALS), $GLOBALS[$key] ?? null];
    }

    $_GET = ['s' => 'heart'];
    if (! defined('WP_PLUGIN_DIR')) {
        define('WP_PLUGIN_DIR', __DIR__.'/../Fixtures/plugins');
    }

    SearchTemplateLoader::load();

    $state = (object) [
        'rowsByType' => [], 'totals' => [], 'rows' => [], 'index' => -1,
        'queries' => [], 'resets' => 0, 'blogEvents' => [], 'fields' => [],
        'pluginAvailable' => false, 'taxExists' => true, 'content' => 'Content', 'readsWithoutAdvance' => 0,
    ];
    $this->searchState = $state;
    when('class_exists')->alias(static fn ($class) => $class === 'UAMSPhysicians' ? $state->pluginAvailable : relay());
    when('esc_html')->alias(htmlspecialchars(...));
    when('get_query_var')->justReturn(2);
    when('uamswp_custom_loop_base')->alias(static function ($args) use ($state): void {
        $state->queries[] = $args;
        $type = is_array($args['post_type']) ? $args['post_type'][0] : $args['post_type'];
        $state->rows = $state->rowsByType[$type] ?? [];
        $state->index = -1;
        $state->readsWithoutAdvance = 0;
        $GLOBALS['wp_query'] = (object) ['found_posts' => $state->totals[$type] ?? count($state->rows)];
    });
    when('have_posts')->alias(static function () use ($state): bool {
        if (++$state->readsWithoutAdvance > 100) {
            throw new RuntimeException('Search loop did not advance.');
        }

        return isset($state->rows[$state->index + 1]);
    });
    when('the_post')->alias(static function () use ($state): void {
        $state->readsWithoutAdvance = 0;
        $row = $state->rows[++$state->index];
        $GLOBALS['post'] = (object) $row;
        $GLOBALS['wp_query']->post = (object) $row;
    });
    when('get_the_id')->alias(static fn () => $GLOBALS['post']->ID);
    when('get_the_title')->alias(static fn ($id = null): string => 'Title '.($id ?? $GLOBALS['post']->ID));
    when('get_the_permalink')->alias(static fn ($id = null): string => 'https://example.test/post/'.($id ?? $GLOBALS['post']->ID));
    when('get_term_by')->alias(static fn (): stdClass|false => $state->taxExists ? (object) ['term_id' => 77] : false);
    when('get_field')->alias(static function ($name, $id) use ($state) {
        $state->fields[] = [$name, $id];

        return $state->content;
    });
    when('wp_trim_words')->alias(static function ($content, $count): string {
        expect($count)->toBe(30);

        return 'Preview '.$content;
    });
    when('has_excerpt')->alias(static fn ($id): bool => $id % 2 === 0);
    when('get_the_excerpt')->alias(static fn ($id): string => 'Excerpt '.$id);
    when('wp_trim_excerpt')->alias(static function ($text, $id): string {
        expect($text)->toBe('');

        return 'Trimmed '.$id;
    });
    when('home_url')->justReturn('https://example.test');
    when('trailingslashit')->alias(static fn ($url): string => rtrim($url, '/').'/');
    when('wp_reset_query')->alias(static function () use ($state): void {
        $state->resets++;
    });
    when('switch_to_blog')->alias(static function ($id) use ($state): void {
        $state->blogEvents[] = ['switch', $id];
    });
    when('restore_current_blog')->alias(static function () use ($state): void {
        $state->blogEvents[] = ['restore'];
    });
});

afterEach(function (): void {
    foreach ($this->savedSearchGlobals as $key => [$existed, $value]) {
        if ($existed) {
            $GLOBALS[$key] = $value;
        } else {
            unset($GLOBALS[$key]);
        }
    }
});

it('includes the right external card for each typed result', function (string $type, string $card): void {
    $_GET['type'] = $type;
    $this->searchState->rowsByType[$type] = [['ID' => 11], ['ID' => 22]];
    Functions\expect('genesis_posts_nav')->once();
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    HtmlFixture::assertSearch('typed-card-'.$type, $html);
    expect($html)->toContain('['.$card.':11]['.$card.':22]')->not->toContain('Sorry, no content')
        ->and($this->searchState->queries)->toBe([['s' => 'heart', 'post_type' => $type, 'posts_per_page' => $type === 'provider' ? 6 : 10, 'paged' => 2]]);
})->with([['provider', 'physician'], ['location', 'location'], ['expertise', 'expertise']]);

it('pins taxonomy content, missing terms and empty fields in typed results', function (string $type, bool $taxExists, $content, bool $article): void {
    $_GET['type'] = $type;
    $state = $this->searchState;
    $state->rowsByType[$type] = [['ID' => 11]];
    $state->taxExists = $taxExists;
    $state->content = $content;

    Functions\expect('genesis_posts_nav')->once();
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    HtmlFixture::assertSearch('typed-tax-'.$type.'-'.(int) $taxExists.'-'.(int) (bool) $content, $html);
    expect(str_contains($html, '<article'))->toBe($article);
    if ($article) {
        expect($html)->toContain('<article class="'.$type.'-'.($taxExists ? '77' : '').' entry">')
            ->toContain('<h2 class="entry-title" itemprop="headline"><a href="https://example.test/post/11">Title 11</a></h2>')
            ->and($state->fields)->toBe([[$type.'_content', $type.'_'.($taxExists ? '77' : '')]])
            ->and(str_contains($html, 'Preview Content... <a class="more-link" href="https://example.test/post/11">Continue Reading</a><p>'))->toBe((bool) $content);
    } else {
        expect($state->fields)->toBe([]);
    }
})->with([
    ['condition', true, 'Content', true], ['condition', true, '', true],
    ['condition', false, 'Content', false], ['treatment', true, 'Content', true],
    ['treatment', false, 'Content', true], ['treatment', false, '', true],
]);

/**
 * The Page/Post labels make StrEndsWithToStrStartsWith equivalent. The title's h3
 * markup is always nonempty, so EmptyStringToNotEmpty on its Elvis fallback is
 * unreachable; empty excerpt content is tested separately.
 */
it('renders general page and post results with excerpts and cross-blog restoration', function (int $total): void {
    $state = $this->searchState;
    $state->rowsByType = ['page' => [['ID' => 12, 'blog_id' => 3], ['ID' => 13]], 'post' => [['ID' => 14, 'blog_id' => 4]]];
    $state->totals = ['page' => $total, 'post' => $total];
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    HtmlFixture::assertSearch('general-'.$total, $html);
    expect($html)->toContain('data-id="12"', 'data-id="13"', 'data-id="14"', '<p>Excerpt 12</p>', '<p>Trimmed 13</p>', '<p>Excerpt 14</p>')
        ->and(substr_count($html, '>More results</a>'))->toBe($total > 5 ? 2 : 0)
        ->and($state->resets)->toBe(2)
        ->and($state->blogEvents)->toBe([['switch', 3], ['restore'], ['restore'], ['switch', 4], ['restore']]);
    if ($total > 5) {
        expect($html)->toContain('href="https://example.test/?s=heart&type=page"', 'href="https://example.test/?s=heart&type=post"');
    }
})->with([5, 6]);

/**
 * Condition/Treatment make StrEndsWithToStrStartsWith equivalent. The first buffered
 * append's ConcatEqualToEqual is equivalent because the buffer is reset to '' directly
 * before it. Later append operations remain covered by complete output assertions.
 */
it('renders plugin groups only when they have results and pins each more-link boundary', function (int $extra, bool $hasTerms): void {
    $state = $this->searchState;
    $state->pluginAvailable = true;
    $state->rowsByType = ['provider' => [['ID' => 11]], 'location' => [['ID' => 22]], 'expertise' => [['ID' => 33]]];
    $state->totals = ['provider' => 6 + $extra, 'location' => 4 + $extra, 'expertise' => 4 + $extra, 'condition' => 5 + $extra, 'treatment' => 5 + $extra];
    if ($hasTerms) {
        $state->rowsByType['condition'] = [['ID' => 44]];
        $state->rowsByType['treatment'] = [['ID' => 55]];
    }

    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    HtmlFixture::assertSearch('plugin-'.$extra.'-'.(int) $hasTerms, $html);
    expect($html)->toContain('[physician:11]', '[location:22]', '[expertise:33]')
        ->and(substr_count($html, '>More results</a>'))->toBe($extra * ($hasTerms ? 5 : 3))
        ->and(str_contains($html, '>Conditions</span>'))->toBe($hasTerms)
        ->and(str_contains($html, '>Treatments</span>'))->toBe($hasTerms)
        ->and($state->resets)->toBe(7)
        ->and($state->queries)->toBe([
            ['s' => 'heart', 'post_type' => 'page', 'posts_per_page' => 5],
            ['s' => 'heart', 'post_type' => 'post', 'posts_per_page' => 5],
            ['s' => 'heart', 'post_type' => ['provider'], 'posts_per_page' => 6],
            ['s' => 'heart', 'post_type' => 'condition', 'posts_per_page' => 5],
            ['s' => 'heart', 'post_type' => 'treatment', 'posts_per_page' => 5],
            ['s' => 'heart', 'post_type' => ['location'], 'posts_per_page' => 4],
            ['s' => 'heart', 'post_type' => ['expertise'], 'posts_per_page' => 4],
        ]);
    if ($hasTerms) {
        expect($html)->toContain('<p>Preview Content<p>')
            ->and($state->fields)->toBe([['condition_content', 'condition_77'], ['treatment_content', 'treatment_77'], ['treatment_procedure_content', 'treatment_77']]);
    } else {
        expect($state->fields)->toBe([]);
    }
})->with([[0, true], [1, true], [0, false], [1, false]]);

it('omits empty plugin card groups and handles missing terms with empty content', function (): void {
    $state = $this->searchState;
    $state->pluginAvailable = true;
    $state->rowsByType = ['condition' => [['ID' => 44]], 'treatment' => [['ID' => 55]]];
    $state->taxExists = false;
    $state->content = '';
    unset($_GET['s']);
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    HtmlFixture::assertSearch('missing-terms-empty-content', $html);
    expect($html)->not->toContain('>Providers</span>', '>Locations</span>', '>Areas of Expertise</span>', '<p>Preview')
        ->toContain('Title 44', 'Title 55', 'Search All UAMS Sites')
        ->and($state->fields)->toBe([['condition_content', 'condition_'], ['treatment_content', 'treatment_'], ['treatment_procedure_content', 'treatment_']])
        ->and(array_column($state->queries, 's'))->toBe(['', '', '', '', '', '', '']);
});

it('omits empty excerpt content without adding whitespace after the title', function (): void {
    $this->searchState->rowsByType = ['page' => [['ID' => 12]]];
    when('get_the_excerpt')->justReturn('');
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    expect($html)->toContain('<h3 class="h4" data-id="12"><a href="https://example.test/post/12">Title 12</a></h3></div></div>');
});

it('retains populated conditions when the treatment group is empty', function (): void {
    $state = $this->searchState;
    $state->pluginAvailable = true;
    $state->rowsByType = ['condition' => [['ID' => 44]]];
    ob_start();
    uamswp_do_search_loop();
    $html = ob_get_clean();
    expect($html)->toContain('Title 44', '<p>Preview Content<p>')
        ->toContain(' <div class="col-12 col-md-6 post-type treatment"><div class="inner-container content-width"><h2 class="module-title post-type-heading"><span class="title">Treatments</span></h2><p>Sorry, no content matched your criteria.</p></div></div>');
});
