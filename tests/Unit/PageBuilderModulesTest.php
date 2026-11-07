<?php

declare(strict_types=1);

use Brain\Monkey\Functions;
use Uams2020\Tests\Support\ModuleTemplateCapture;
use Uams2020\Tests\Support\OptionString;

use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

beforeEach(function (): void {
    ModuleTemplateCapture::$calls = [];
    when('get_stylesheet_directory')->justReturn(__DIR__.'/../Fixtures/page-builder-theme');
    when('class_exists')->alias(static fn ($name) => $name === 'UAMS_Syndicate_News_Base' ? true : relay());
});

function pageBuilderModuleInput(string $layout): array
{
    $inputs = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/module-inputs.json'), true, flags: JSON_THROW_ON_ERROR);
    $module = $inputs[$layout];
    if (isset($module['post_tile_category'])) {
        $module['post_tile_category'] = (object) $module['post_tile_category'];
    }

    return $module;
}

function capturePageBuilderModule(array $module, $index = 7): string
{
    ob_start();
    try {
        uamswp_module($module, $index);

        return ob_get_contents();
    } finally {
        ob_end_clean();
    }
}

it('passes each layout its exact template variables and wrapper markup', function (string $layout): void {
    $expected = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/module-dispatch.json'), true, flags: JSON_THROW_ON_ERROR)[$layout];
    expect(capturePageBuilderModule(pageBuilderModuleInput($layout)))->toBe($expected['html'])
        ->and(ModuleTemplateCapture::$calls)->toBe($expected['calls']);
})->with([
    'modules_action_bar', 'modules_call_out', 'modules_cta', 'modules_hero', 'modules_link_list',
    'modules_image_side', 'modules_text_overlay', 'modules_post_tile', 'modules_post_tiles',
    'modules_text_stacked', 'modules_news_grid', 'modules_uams_gallery', 'modules_uams_content', 'modules_uams_livewhale',
]);

it('preserves defaults and quote selection in image-side module input', function (string $layout, string $body, string $quote): void {
    $module = pageBuilderModuleInput('modules_image_side');
    foreach (['side_heading', 'side_layout_body_text', 'side_layout_link_text', 'side_link_list', 'side_link_include_more', 'side_cta', 'side_image_position', 'side_image_background_color'] as $key) {
        $module[$key] = '';
    }

    $module['side_text_layout'] = $layout;
    $module['side_quote_text'] = 'plain quote';
    $module['side_quote_text_link'] = 'linked quote';
    $module['side_image']['side_image_anchor'] = '';
    unset($module['side_quote_speaker_name'], $module['side_quote_speaker_title']);
    capturePageBuilderModule($module, false);
    $actual = ModuleTemplateCapture::$calls[0]['variables'];
    $expected = [
        'i' => false, 'id' => false, 'layout' => $layout ?: 'link-list', 'heading' => 'Heading goes here...',
        'body' => $body, 'quote_text' => $quote, 'quote_name' => '', 'quote_title' => '',
        'link_list' => '', 'list_more' => '', 'cta' => '', 'image_group' => $module['side_image'],
        'image_postion' => 'left', 'image_anchor' => 'center', 'background_color' => 'bg-white',
        'geo' => $module['side_image_geo'], 'geo_region' => $module['side_image_geo_region'],
    ];
    ksort($expected);
    expect($actual)->toBe($expected);
})->with([
    ['', 'This is where the body + link list description goes', 'plain quote'],
    ['body-only', 'This is where the body-only description goes', 'plain quote'],
    ['blockquote-citation', '', 'plain quote'], ['blockquote-citation-link', '', 'linked quote'],
]);

it('passes empty image and absent quote values through the image-side fallbacks', function (): void {
    $module = pageBuilderModuleInput('modules_image_side');
    $module['side_image'] = ['side_image_anchor' => ''];
    unset($module['side_quote_text'], $module['side_quote_text_link']);
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[0]['variables']['quote_text'])->toBe('');
});

it('pins the false-image fallback and its existing anchor-offset warning', function (): void {
    $module = pageBuilderModuleInput('modules_image_side');
    $module['side_image'] = false;
    $warnings = [];
    $source = dirname(__DIR__, 2).'/lib/modules.php';
    $message = 'Trying to access array offset on false';
    $renderer = new ReflectionFunction('uamswp_module');
    set_error_handler(static function (int $severity, string $text, string $file, int $line) use (&$warnings, $source, $message, $renderer): bool {
        if ($severity !== E_WARNING || realpath($file) !== realpath($source) || $line < $renderer->getStartLine() || $line > $renderer->getEndLine() || $text !== $message) {
            throw new ErrorException($text, 0, $severity, $file, $line);
        }

        $warnings[] = [$severity, $text];

        return true;
    });
    try {
        capturePageBuilderModule($module);
    } finally {
        restore_error_handler();
    }

    expect($warnings)->toBe([[E_WARNING, $message]])
        ->and(ModuleTemplateCapture::$calls[0]['variables']['image_group'])->toBe('')
        ->and(ModuleTemplateCapture::$calls[0]['variables']['image_anchor'])->toBe('center');
});

it('keeps CTA target absent without a button and applies post-tile text defaults', function (): void {
    $module = pageBuilderModuleInput('modules_cta');
    $module['cta_bar_button_url'] = '';
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[0]['variables'])->not->toHaveKey('button_target');
    $module = pageBuilderModuleInput('modules_post_tile');
    $module['post_tile_post_button_text'] = '';
    $module['post_tile_category_button_text'] = '';
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[1]['variables']['post_button_text'])->toBe('Read the Story')
        ->and(ModuleTemplateCapture::$calls[1]['variables']['cat_button_text'])->toBe('View Research Archive');
});

it('normalizes gallery crop and chooses more-button defaults by background', function ($crop, bool $more, string $background, string $color): void {
    $module = pageBuilderModuleInput('modules_uams_gallery');
    $module['gallery_crop'] = $crop;
    $module['gallery_more'] = $more;
    $module['gallery_background_color'] = $background;
    foreach (['gallery_more_text', 'gallery_more_button_text', 'gallery_more_button_url', 'gallery_more_button_description'] as $key) {
        unset($module[$key]);
    }

    capturePageBuilderModule($module);
    $vars = ModuleTemplateCapture::$calls[0]['variables'];
    expect($vars['gallery_crop'])->toBe($crop === 'none' || $crop === ['none'] ? -1 : $crop);
    if ($more) {
        foreach (['more_text', 'more_button_text', 'more_button_url', 'more_button_target', 'more_button_description'] as $key) {
            expect($vars[$key])->toBe('');
        }

        expect($vars['more_button_color'])->toBe($color);
    } else {
        expect($vars)->not->toHaveKey('more_button_color');
    }
})->with([
    ['none', true, 'bg-white', 'primary'], [['none'], true, 'bg-gray', 'primary'],
    [['square'], true, 'bg-auto', 'primary'], [['wide'], true, 'bg-red', 'white'],
    [['none'], false, 'bg-white', ''],
]);

it('forces news count only for the four fixed formats', function (string $format, string $count): void {
    $module = pageBuilderModuleInput('modules_news_grid');
    $module['news_format'] = $format;
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[0]['variables']['count'])->toBe($count);
})->with([['grid', '3'], ['cards', '4'], ['full', '1'], ['side', '1'], ['other', 'value:news_count']]);

/** The untyped dispatcher retains PHP string comparisons for filtered, stringable values. */
it('preserves stringable layout and quote comparisons at the public module boundary', function (string $layout, string $body, string $quote): void {
    $module = pageBuilderModuleInput('modules_image_side');
    $module['side_text_layout'] = new OptionString($layout);
    $module['side_quote_text'] = 'plain quote';
    $module['side_quote_text_link'] = 'linked quote';
    capturePageBuilderModule($module);
    $variables = ModuleTemplateCapture::$calls[0]['variables'];
    expect($variables['body'])->toBe($body)->and($variables['quote_text'])->toBe($quote);
})->with([
    ['body-only', 'value:side_layout_body_text', 'plain quote'],
    ['blockquote-citation', '', 'plain quote'],
    ['blockquote-citation-link', '', 'linked quote'],
]);

it('preserves each stringable news-format comparison independently', function (string $format, string $count): void {
    $module = pageBuilderModuleInput('modules_news_grid');
    $module['news_format'] = new OptionString($format);
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[0]['variables']['count'])->toBe($count);
})->with([['grid', '3'], ['cards', '4'], ['full', '1'], ['side', '1']]);

it('keeps stringable crop none short-circuiting and mixed crop elements normalized', function ($crop): void {
    $module = pageBuilderModuleInput('modules_uams_gallery');
    $module['gallery_crop'] = $crop;
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[0]['variables']['gallery_crop'])->toBe(-1);
})->with([[new OptionString('none')], [[true]]]);

it('preserves each stringable more-button background comparison independently', function (string $background): void {
    $module = pageBuilderModuleInput('modules_uams_gallery');
    $module['gallery_background_color'] = new OptionString($background);
    capturePageBuilderModule($module);
    expect(ModuleTemplateCapture::$calls[0]['variables']['more_button_color'])->toBe('primary');
})->with(['bg-white', 'bg-gray', 'bg-auto']);

it('recognizes the stringable disabled layout only for a signed-in visitor', function (bool $signedIn): void {
    when('is_user_logged_in')->justReturn($signedIn);
    expect(uamswp_module_disable(['acf_fc_layout' => new OptionString('save_recipes_cta')]))->toBe($signedIn);
})->with([false, true]);

it('skips news output without its plugin but retains the wrapper', function (): void {
    when('class_exists')->alias(static fn ($name) => $name === 'UAMS_Syndicate_News_Base' ? false : relay());
    expect(capturePageBuilderModule(pageBuilderModuleInput('modules_news_grid')))->toBe('<!-- // Begin Module - modules_news_grid// --><!-- // End Module - modules_news_grid// -->')
        ->and(ModuleTemplateCapture::$calls)->toBe([]);
});

it('skips empty layouts, preserves unknown wrappers and hides signed-in save-recipe modules', function (): void {
    expect(capturePageBuilderModule([]))->toBe('')
        ->and(capturePageBuilderModule(['acf_fc_layout' => 'unknown']))->toBe('<!-- // Begin Module - unknown// --><!-- // End Module - unknown// -->');
    when('is_user_logged_in')->justReturn(true);
    expect(capturePageBuilderModule(['acf_fc_layout' => 'save_recipes_cta']))->toBe('');
    when('is_user_logged_in')->justReturn(false);
    expect(capturePageBuilderModule(['acf_fc_layout' => 'save_recipes_cta']))->toBe('<!-- // Begin Module - save_recipes_cta// --><!-- // End Module - save_recipes_cta// -->');
});

it('loads modules from the explicit post or current post and preserves their order and indices', function ($post, int $expected): void {
    when('get_the_id')->justReturn(91);
    $modules = [3 => ['acf_fc_layout' => 'first'], 8 => ['acf_fc_layout' => 'second']];
    Functions\expect('get_field')->once()->with('uamswp_modules', $expected)->andReturn($modules);
    $calls = [];
    when('uamswp_module')->alias(static function ($module, $index) use (&$calls): void {
        $calls[] = [$module, $index];
    });
    uamswp_modules($post);
    expect($calls)->toBe([[$modules[3], 3], [$modules[8], 8]]);
})->with([[false, 91], ['42', 42], [0, 91], ['12.9', 12]]);

it('returns early when ACF is absent or the module field is empty', function (): void {
    when('get_field')->justReturn([]);
    when('get_the_id')->justReturn(91);
    when('function_exists')->alias(static fn ($name) => $name === 'get_field' ? false : relay());
    expect(uamswp_modules())->toBeNull()->and(uamswp_has_module())->toBeNull();
    when('function_exists')->alias(static fn ($name) => $name === 'get_field' ? true : relay());
    expect(uamswp_modules())->toBeNull()->and(uamswp_has_module())->toBeFalse();
});

it('does no field or post lookup when ACF is unavailable', function (): void {
    Functions\expect('get_field')->never();
    Functions\expect('get_the_id')->never();

    when('function_exists')->alias(static fn ($name) => $name === 'get_field' ? false : relay());
    expect(uamswp_modules())->toBeNull()->and(uamswp_has_module())->toBeNull();
});

it('uses the current post when the modules post argument is omitted', function (): void {
    when('get_the_id')->justReturn(91);
    $module = ['acf_fc_layout' => 'modules_action_bar'];
    Functions\expect('get_field')->once()->with('uamswp_modules', 91)->andReturn([$module]);
    Functions\expect('uamswp_module')->once()->with($module, 0);
    uamswp_modules();
});

it('retains the empty layout lookup and current post when both lookup arguments are omitted', function (): void {
    when('get_the_id')->justReturn(91);
    Functions\expect('get_field')->once()->with('uamswp_modules', 91)->andReturn([['acf_fc_layout' => '']]);
    expect(uamswp_has_module())->toBeTrue();
});

it('passes the default false index to a directly rendered module', function (): void {
    ob_start();
    uamswp_module(pageBuilderModuleInput('modules_action_bar'));
    $html = ob_get_clean();
    expect($html)->toBe('<!-- // Begin Module - modules_action_bar// -->[template:action-bar.php]<!-- // End Module - modules_action_bar// -->')
        ->and(ModuleTemplateCapture::$calls[0]['variables']['i'])->toBeFalse()
        ->and(ModuleTemplateCapture::$calls[0]['variables']['id'])->toBeFalse();
});

it('searches all module layouts using the current loose comparison', function ($needle, array $modules, bool $expected, $post, int $postId): void {
    when('get_the_id')->justReturn(91);
    Functions\expect('get_field')->once()->with('uamswp_modules', $postId)->andReturn($modules);
    expect(uamswp_has_module($needle, $post))->toBe($expected);
})->with([
    ['match', [['acf_fc_layout' => 'other'], ['acf_fc_layout' => 'match']], true, false, 91],
    ['match', [['acf_fc_layout' => 'match'], ['acf_fc_layout' => 'other']], true, '42.9', 42],
    ['missing', [['acf_fc_layout' => 'other']], false, 0, 91],
    [1, [['acf_fc_layout' => '1']], true, 7, 7], ['', [['acf_fc_layout' => '']], true, false, 91],
]);

it('escapes module headings and preserves the empty display helper output', function (): void {
    when('esc_html')->alias(static fn ($value): string => htmlspecialchars($value, ENT_QUOTES));
    ob_start();
    uamswp_module_header(['title' => '<Title>&']);
    uamswp_module_header(['title' => '']);
    uamswp_module_header([]);
    expect(ob_get_clean())->toBe('<header><h3>&lt;Title&gt;&amp;</h3></header>');
    ob_start();
    display_call_out(1, 'class', 'heading', 'body', true, 5, 'bg-white');
    expect(ob_get_clean())->toBe("    \n    ");
});

it('renders section module boundaries and each supported row', function ($index, string $anchor, bool $hidden, array $rows, bool $lyte, string $id, string $body): void {
    when('esc_attr')->alias(static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES));
    when('esc_html')->alias(static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES));
    when('sanitize_title_with_dashes')->alias(static function ($value): string {
        expect($value)->toBe('Custom Anchor');

        return 'custom-anchor';
    });
    when('lyte_parse')->alias(static function ($value): string {
        expect($value)->toBe('httpv://youtube.test/watch');

        return '[lyte]';
    });
    when('wp_oembed_get')->alias(static fn ($value): string => '[embed:'.$value.']');
    when('function_exists')->alias(static fn ($name) => $name === 'lyte_preparse' ? $lyte : relay());

    $module = ['acf_fc_layout' => 'modules_uams_section', 'modules_uams_section_heading' => '<Title>&',
        'modules_uams_section_hide_heading' => $hidden, 'modules_uams_section_background_color' => 'bg-"blue',
        'modules_uams_section_flexible_layout' => $rows, 'anchor_id' => $anchor];
    $html = capturePageBuilderModule($module, $index);
    $expected = '<!-- // Begin Module - modules_uams_section// -->                    <section class="uams-module section-block bg-&quot;blue" id="uams-section-'.$id.'" aria-label="&lt;Title&gt;&amp;">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="module-title '.($hidden ? ' sr-only' : '').'">
                                        <span class="title">&lt;Title&gt;&amp;</span>
                                    </h2>
                                    '.$body.'                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- // End Module - modules_uams_section// -->';
    expect($html)->toBe($expected);
})->with([
    [7, '', false, [], false, '7', ''],
    [0, '', true, [['acf_fc_layout' => 'uams_section_wysiwyg', 'section_wysiwyg_html' => '<p>Raw content</p>']], false, 'module-1', '<div class="module-body"><p>Raw content</p></div>'],
    [false, 'Custom Anchor', false, [['acf_fc_layout' => 'modules_uams_section_youtube', 'section_youtube_url' => 'https://youtube.test/watch', 'section_youtube_width' => 'wide']], true, 'custom-anchor', '<div class="module-body"><div class="wide">[lyte]</div></div>'],
    [0, '', false, [['acf_fc_layout' => 'modules_uams_section_youtube', 'section_youtube_url' => 'https://youtube.test/watch', 'section_youtube_width' => 'wide']], false, 'module-1', '<div class="module-body"><div class="wide wp-block-embed is-type-video embed-responsive wp-has-aspect-ratio embed-responsive-16by9">[embed:https://youtube.test/watch]</div></div>'],
    [2, '', false, [['acf_fc_layout' => 'modules_uams_section_youtube', 'section_youtube_url' => 'https://other.test/watch', 'section_youtube_width' => 'narrow'], ['acf_fc_layout' => 'unknown']], true, '2', '<div class="module-body"><div class="narrow wp-block-embed is-type-video embed-responsive wp-has-aspect-ratio embed-responsive-16by9">[embed:https://other.test/watch]</div></div>'],
    [2, '', false, [['acf_fc_layout' => new OptionString('uams_section_wysiwyg'), 'section_wysiwyg_html' => '<p>Filtered</p>']], false, '2', '<div class="module-body"><p>Filtered</p></div>'],
    [2, '', false, [['acf_fc_layout' => new OptionString('modules_uams_section_youtube'), 'section_youtube_url' => 'https://youtube.test/watch', 'section_youtube_width' => 'wide']], false, '2', '<div class="module-body"><div class="wide wp-block-embed is-type-video embed-responsive wp-has-aspect-ratio embed-responsive-16by9">[embed:https://youtube.test/watch]</div></div>'],
]);
